from __future__ import print_function
from pprint import pprint
import boto3
import json
from elasticsearch import Elasticsearch, RequestsHttpConnection
import urllib
import zlib

s3 = boto3.client('s3')

print('Loading function')

indexDoc = {
    "dataRecord" : {
        "properties" : {
          "createdDate" : {
            "type" : "date",
            "format" : "dateOptionalTime"
          },
          "objectKey" : {
            "type" : "string"
          },
          "content_type" : {
            "type" : "string"
          },
          "content_length" : {
            "type" : "long"
          },
          "metadata" : {
            "type" : "string"
          }
        }
      },
    "settings" : {
        "number_of_shards": 1,
        "number_of_replicas": 0
      }
    }


def connectES(esEndPoint):
    print ('Connecting to the ES Endpoint {0}'.format(esEndPoint))
    try:
        esClient = Elasticsearch(
            hosts=[{'host': esEndPoint, 'port': 443}],
            use_ssl=True,
            verify_certs=True,
            connection_class=RequestsHttpConnection)
        return esClient
    except Exception as E:
        print("Unable to connect to {0}".format(esEndPoint))
        print(E)
        exit(3)

def createIndex(esClient,index_name):
    try:
        res = esClient.indices.exists(index_name)
        if res is False:
            esClient.indices.create(index_name, body=indexDoc)
        return 1
    except Exception as E:
        print("Unable to Create Index {0}".format(index_name))
        print(E)
        exit(4)

def indexDocElement(esClient,key,response):
    try:
        indexObjectKey = key
        indexcreatedDate = response['LastModified']
        indexcontent_length = response['ContentLength']
        indexcontent_type = response['ContentType']
        indexmetadata = json.dumps(response['Metadata'])
        ext=key.split('.')[-1];
        images = ['png','gif','tif','jpg','jpeg','iff','ico','tiff','pct','pict','svg']
        archivalAndcompression = ['a','ar','cpio','shar','iso','lbr','mar','sbx','jar','war','tar','bz2','f','gz','tgz','bz2','tbz2','tlz','lz','lzma','lzo','rz','sfark','sz','?q?','?z?','?xf','xz','z','Z','??_','.7z','.s7z','ace','afa','alz','apk','arc','cab','car','ice','lzh','lzx','pak','rar','zip','sfx','xar','zipx','rev','gzip']
        documents = ['doc','docm','docx','dot','dotx','acl','gdoc','mobi','odt','pdf','dvi','pld','egt','pcl','ps','snp','xps','ppt','pptx','afp','gslides','key','keynote','odt','otp','pez','prz','sti','sxi','watch','xls','xlsx','123','cell','csv','gsheet','numbers','gnumeric','ods','ots','xlk','xlsb','xlsm','xlr','xlt','xltm','xlw']
        text = ['text','txt','tsv','tex','pages','stw','sxw','xml','0','1st','600','602','ans','asc','epub','log','nb','css','xsl','xslt','tpl','bib','enl','ris']
        code = ['c','cpp','java','py','sh','html','xhtml','phtml','mhtml','shtml','class','bat','cmd','ipynb','js','jsfl','pl','php','ps1','vbs','r','rb','cs','go','lisp','scala','asp','jsp','aspx','sql','sqlite']
        hadoop_fileFormats=['avro','parquet','json','orc']
        if ext.lower() in [x.lower() for x in images]:
            type = 'Images'
        elif ext.lower() in [x.lower() for x in archivalAndcompression]:
            type = 'Archival or Compression'
        elif ext.lower() in [x.lower() for x in documents]:
            type = 'Documents'
        elif ext.lower() in [x.lower() for x in text]:
            type = 'Text'
        elif ext.lower() in [x.lower() for x in code]:
            type = 'Code'
        elif ext.lower() in [x.lower() for x in hadoop_fileFormats]:
            type = 'Hadoop_fileFormats'
        else:
            type = 'File'
        retval = esClient.index(index='metadata-store', doc_type=type, body={
                'createdDate': indexcreatedDate,
                'objectKey': indexObjectKey,
                'content_type': indexcontent_type,
                'content_length': indexcontent_length,
                'metadata': indexmetadata
        })
    except Exception as E:
        print("Document not indexed")
        print("Error: ",E)
        exit(5) 
        
def lambda_handler(event, context):
    esClient = connectES("oldelasticsearchep")
    createIndex(esClient,'metadata-store')
    createIndex(esClient,'cloudtraillogs')
    bucket = event['Records'][0]['s3']['bucket']['name']
    key = urllib.unquote_plus(event['Records'][0]['s3']['object']['key'].encode('utf8'))
    print("key : ",key)
    validate_key = key.rsplit("/",1)[0]+"/"
    filter_key = key.split("/")[0]+"/"
    if filter_key == "TaskRunnerLogs/":
        print("skipped logging for taskrunner logs")
    elif filter_key == "CloudTrailLogs/":
    	try:
    	    retr = s3.get_object(Bucket=bucket, Key=key)
            body = retr['Body']
            decc_data=zlib.decompress(body.read(),zlib.MAX_WBITS|16)
            bulk_load=[]
            data=json.loads(decc_data)
            if 'Records' in data:
                records=data['Records']
                for item in records:
                    op_dict = {
                        "index": {
                        "_index": 'cloudtraillogs',
                        "_type": 'log'
                        }
                    }
                    bulk_load.append(op_dict)
                    bulk_load.append(item)
                    print (bulk_load)
                res=esClient.bulk(index = 'cloudtraillogs', body = bulk_load, refresh = True)
        except Exception as E:
            print("Document not indexed")
            print("Error: ",E)
            exit(5)
    else:
        try:
            response = s3.get_object(Bucket=bucket, Key=key)
            indexDocElement(esClient,key,response)
        except Exception as e:
            print(e)
            print('Error getting object {} from bucket {}. Make sure they exist and your bucket is in the same region as this function.'.format(key, bucket))
            raise e
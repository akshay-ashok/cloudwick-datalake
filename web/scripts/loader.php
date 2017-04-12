<style>
    body {
        overflow: hidden;
    }

    .loading div {
        width: 30px;
        height: 30px;
        position: absolute;
        top: 45%;
        border-radius: 50%;
    }
    .loading .round-trip {
        background-color: #87CDCD;
        -webkit-animation: move 2s infinite cubic-bezier(0.2, 0.64, 0.81, 0.23);
        animation: move 2s infinite cubic-bezier(0.2, 0.64, 0.81, 0.23);
    }
    .loading .open-jaw {
        background-color: #FF9D84;
        -webkit-animation: move 2s 150ms infinite cubic-bezier(0.2, 0.64, 0.81, 0.23);
        animation: move 2s 150ms infinite cubic-bezier(0.2, 0.64, 0.81, 0.23);
    }
    .loading .one-way {
        background-color: #F0E797;
        -webkit-animation: move 2s 300ms infinite cubic-bezier(0.2, 0.64, 0.81, 0.23);
        animation: move 2s 300ms infinite cubic-bezier(0.2, 0.64, 0.81, 0.23);
    }

    @-webkit-keyframes move {
        0% {
            left: 0%;
        }
        100% {
            left: 100%;
        }
    }

    @keyframes move {
        0% {
            left: 0%;
        }
        100% {
            left: 100%;
        }
    }
</style>
<div class="loading">
    <div class="round-trip"></div>
    <div class="open-jaw"></div>
    <div class="one-way"></div>
</div>
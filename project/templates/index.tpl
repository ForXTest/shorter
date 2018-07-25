<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <link href="/static/css/style.css?v=1" rel="stylesheet">
    <script src="/static/js/request.js?v=1"></script>
    <title>Shortener</title>
</head>
<body>
<div class="page">
    <div class="page__form">
        <form onsubmit="javascript:makeShortUrl(); return false;">
            <div class="form">
                <div class="form__long-url">
                    <label for="name">
                        Long URL
                    </label>
                    <input type="url" class="input-text" id="url" name="url">

                    <input type="submit" class="btn" value="Short!">
                </div>
                <div class="form__error" id="error"></div>
                <div class="form__short-url">
                    <label>
                        Short URL
                    </label>
                </div>
                <div class="form__result">
                    <div id="result"></div>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>

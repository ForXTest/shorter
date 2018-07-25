function getXmlHttp() {
    var xmlhttp;
    try {
        xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
    } catch (e) {
        try {
            xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
        } catch (E) {
            xmlhttp = false;
        }
    }

    if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
        xmlhttp = new XMLHttpRequest();
    }

    return xmlhttp;
}

function makeShortUrl() {
    var req = getXmlHttp();
    var resultElem = document.getElementById('result');
    var result = {};

    req.onreadystatechange = function () {
        if (parseInt(req.readyState, 10) === 4) {
            if (req.status !== 200) {
                alert('Internal service error!');
                return false;
            }

            if (req.responseText) {
                result = req.responseText;
            } else {
                result.success = 0;
                result.error = 'Internal service error: ' + req.status + ' + ' + req.statusText;
                resultElem.innerHTML = '';
            }

            console.log(result);

            if (!result.error) {
                document.getElementById('error').innerText = '';
                result = JSON.parse(result);
                resultElem.innerHTML = '<a href="' + result.result + '" target="_blank">' + result.result + '</a>';
                return true;
            }

            document.getElementById('error').innerText = result.error;
            return false;
        }
    };

    var url = document.getElementById('url').value;

    if (url == '') {
        document.getElementById('error').innerText = 'Empty long URL';
        return false;
    }

    var params = 'url=' + url;

    req.open('POST', '/', true);
    req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    // req.setRequestHeader('Content-Type', 'application/json')
    req.send(params);

    resultElem.innerHTML = 'Waiting answer ...';
    return false;
}
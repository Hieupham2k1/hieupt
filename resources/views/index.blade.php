<!DOCTYPE html>
<html>
    <head>
        <style>
            #showContainer {
                display: none;
                padding: 5px;
                position: fixed; 
                background: lightblue; 
                border: 5px solid white;
                //font: 25px;
            }
        </style>
    </head>
    <body onmouseup="followMouse(event)">
        URL: <input id="url" onchange="getPdf()" value="pdf/sample.pdf" /><br>
        <div id="showContainer" onmousedown="drag(true)" onmouseup="drag(false)">
            <button id="translateButton" onclick="showTranslate()" class="btn">Dịch?</button>
            <span id="show"></span>
        </div>
        Content:<br>
        <div id="content" onmouseup="showTranslateContainer()"></div>
        <script>
            const _transContainer = document.getElementById('showContainer');
            const _transContent = document.getElementById('show');
            const _transButton = document.getElementById('translateButton');

            const constructor = () => {
                getPdf();
            }
            
            const getPdf = () => {
                // TODO get doc, docx, txt
                const url = document.getElementById('url').value;
                const xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (this.readyState === this.DONE) {
                        document.getElementById('content').innerHTML = this.response;
                    }
                }
                xhr.open("GET", url);
                xhr.send();
            }

            const getSelectedText = () => {
                if (window.getSelection) { 
                    return window.getSelection(); 
                } 
                else if (document.getSelection) { 
                    return document.getSelection(); 
                } 
                else if (document.selection) { 
                    return document.selection.createRange().text; 
                }
            }
            
            const showTranslateContainer = () => {
                var selectedText = getSelectedText().toString();
                if(selectedText.replaceAll(' ', '').length > 0){
                    _transContainer.style.display = "block";
                    _transContent.innerHTML = "";
                    _transButton.style.display = "block";
                }else{
                    _transContainer.style.display = "none";
                }
            }

            const showTranslate = () => {
                var selectedText = getSelectedText().toString();
                translate(selectedText)
                .then(output => {
                    // TODO other matches
                    /*output.matches.forEach(val => {
                        if(val.target == 'vi-VN'){
                            output += val.segment + ': ' + val.translation + ' (' + val.source + ')<br>';
                        }
                    });*/
                    _transContent.innerHTML = output.responseData.translatedText;
                })
                .catch(error => {
                    console.log(error);
                })
                _transButton.style.display = "none";
            }

            const drag = (isDragging) => {
                if(isDragging){
                    document.body.addEventListener('mousemove', followMouse);
                }
                else{
                    document.body.removeEventListener('mousemove', followMouse);
                }
            }

            const followMouse = (event) => {
                if(_transContainer.style.display != 'none'){
                    _transContainer.style.left = event.clientX + "px";
                    _transContainer.style.top = event.clientY + "px";
                }
            }

            const translate = (input) => {
                return new Promise((resolve, reject) => {
                    const url = "https://translated-mymemory---translation-memory.p.rapidapi.com/api/get?langpair=en%7Cvi&q="
                    + input
                    + "&mt=1&onlyprivate=0&de=a%40b.c";
                    const xhr = new XMLHttpRequest();

                    _transContent.innerHTML = (input) ? "đang dịch..." : 'chưa chọn gì!';

                    xhr.onreadystatechange = () => {
		                if (xhr.readyState == 4 && xhr.status == 200) {
                            resolve(JSON.parse(xhr.response));
                        }
                    }
                    xhr.open("GET", url);
                    xhr.setRequestHeader("x-rapidapi-key", "82b4523bb1msh027f0e920f1c9d8p1b1294jsn973b1c68260a");
                    xhr.setRequestHeader("x-rapidapi-host", "translated-mymemory---translation-memory.p.rapidapi.com");
                    xhr.send();
                })
            }

            constructor();
        </script>
    </body>
</html>
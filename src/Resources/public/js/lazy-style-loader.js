document.addEventListener("DOMContentLoaded",function () {
    document.getElementsByClassName("fileLazyLoaderStylePath")[0].addEventListener("DOMNodeInserted",function (event) {
        setTimeout(function () {
            document.getElementById("save").click();
        },500);
    });
});

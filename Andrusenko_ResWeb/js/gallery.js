let galleryImages = document.querySelectorAll(".gallery-img");

if (galleryImages) {
    galleryImages.forEach((image, index) => {
        image.onclick = () => {
            let getElementCss = window.getComputedStyle(image);
            let getFullImgUrl = getElementCss.getPropertyValue("background-image");
            let getImgUrlPos = getFullImgUrl.split("/resources/");
            let setNewImgUrl = getImgUrlPos[1].replace('")', '');

            let container = document.body;
            let newImageWindow = document.createElement("div");
            container.appendChild(newImageWindow);
            newImageWindow.setAttribute("class", "img-window");
            newImageWindow.setAttribute("onclick", "closeImg()");

            let newImg = document.createElement("img");
            newImageWindow.appendChild(newImg);
            newImg.setAttribute("src", "resources/full/"+setNewImgUrl);

        }
    });
}

function closeImg() {
    document.querySelector(".img-window").remove();
}
// 
// Init
// 
document.addEventListener("mousemove", init);

function init() {
    for (let i = 0; i < 8; i++) {
        const element = document.createElement("span");
        element.setAttribute("value", Math.floor((Math.random() - 0.5) * 50));
        switch (Math.floor(Math.random() * 3) + 1) {
            case 1:
                element.innerHTML = '<i class="fa-regular fa-file"></i>';
                break;
            case 2:
                element.innerHTML = '<i class="fa-regular fa-folder"></i>';
                break;
            case 3:
                element.innerHTML = '<i class="fa-regular fa-file-zipper"></i>';
                break;
        }
        document.querySelector(".parallax-wrap").append(element);
    }
    document.querySelectorAll(".parallax-wrap span").forEach((element) => {
        element.style.top = Math.floor(Math.random() * 60 + 20) + "%";
        element.style.left = Math.floor(Math.random() * 60 + 20) + "%";
        element.style.opacity = Math.random();
    });

    // Show parallax elements
    document.querySelector(".parallax-wrap").style.opacity = 1;

    // Remove init eventlistener
    document.removeEventListener("mousemove", init);
}

// 
// The main function
// 
document.addEventListener("mousemove", parallax);

function parallax(event) {
    this.querySelectorAll(".parallax-wrap span").forEach((shift) => {
        const position = shift.getAttribute("value");
        const x = (window.innerWidth - event.pageX * position) / 90;
        const y = (window.innerHeight - event.pageY * position) / 90;

        shift.style.transform = `translateX(${x}px) translateY(${y}px)`;
    });
}
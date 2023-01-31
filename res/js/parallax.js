// 
// Init
// 
document.addEventListener("mousemove", init);

function init() {
    // On hoverable devices spawn 3 elements
    // On non hoverable devices spawn 5 - 10
    let spawns;
    if (window.matchMedia('(hover:none)').matches) spawns = 3;
    else spawns = Math.floor(Math.random() * 5) + 5;
    // Spawn the elements
    for (let i = 0; i < spawns; i++) {
        const element = document.createElement("span");
        let multiplicator = 0;
        while (multiplicator == Math.floor(Math.abs(multiplicator / 3))) {
            multiplicator = Math.floor((Math.random() - 0.5) * 50);
        }
        element.setAttribute("value", multiplicator);
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
        element.style.opacity = Math.random() * 0.8 + 0.2;
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
document.addEventListener("mousemove", e => console.log(e));

function parallax(event, this_sel) {
    if (this_sel == undefined) this_sel = this;
    this_sel.querySelectorAll(".parallax-wrap span").forEach((shift) => {
        const position = shift.getAttribute("value");
        const x = (window.innerWidth - event.pageX * position) / 90;
        const y = (window.innerHeight - event.pageY * position) / 90;

        shift.style.transform = `translateX(${x}px) translateY(${y}px)`;
    });
}

// On touch screen
if (window.matchMedia('(hover:none)').matches) window.setInterval(() => {
    parallax({
        pageX: Math.random() * window.innerWidth * 2,
        pageY: Math.random() * window.innerHeight * 2
    }, document);
}, 4000);
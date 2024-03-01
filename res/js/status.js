// 
// Github: https://github.com/statusjs/statusjs
// 

function status_container_init() {
    statuscontainer = document.getElementsByClassName("statuscontainer");
    if (statuscontainer.length == 0) {
        console.log("No element with class \"statuscontainer\" and \"status\" found!");
    }
}

function status_style_init() {
    // 
    var head = document.getElementsByTagName('head')[0];
    var link = document.createElement('link');
    link.rel = 'stylesheet';
    link.type = 'text/css';
    link.href = 'https://use.fontawesome.com/releases/v5.7.1/css/all.css';
    link.media = 'all';
    head.appendChild(link);
    // 
    var style = document.createElement("style");
    style.innerHTML = ".status_container > div{margin-top:2rem;color:white;border-top-left-radius:1rem;border-top-right-radius:1rem;width:300px;height:75px;padding:0rem;display:block;position:relative;}";
    style.innerHTML += ".status_container > *{color:white; cursor: pointer;}";
    style.innerHTML += ".status_negative{background-color: #bb1e10;}";
    style.innerHTML += ".status_positive{background-color: #2e9b20;}";
    style.innerHTML += ".status_neutral{background-color: #999910;}";
    style.innerHTML += ".status_timebar{transform:translateY(1rem);width:100%;height:1rem;position: absolute;bottom:0;left:0;border-bottom-left-radius:1rem;border-bottom-right-radius:1rem;}";
    style.innerHTML += ".status_timebar>div{background-color: #dddddd;width:100%;height:1rem;position: absolute;bottom:0;left:0;border-bottom-left-radius:1rem;border-bottom-right-radius:1rem;}";
    style.innerHTML += ".status_box_grid {display: grid;grid-template-columns: 2rem calc(100% - 2rem);grid-template-rows: 100%;grid-template-areas: 'status_icon status_text';text-align: center;height: 100%;width: calc(100% - 2rem);margin-left: 1rem;font-size: 1.25rem;}";
    style.innerHTML += ".status_text {grid-area: status_text;}";
    style.innerHTML += ".status_icon {grid-area: status_icon;}";
    style.innerHTML += ".status_box_grid div {display: flex;justify-content: center;align-items: center;}";
    style.innerHTML += "";
    document.getElementsByTagName("body")[0].appendChild(style);
}

status_container_init();
status_style_init();

var notif_number = 0;

function status_notify(message, type = "neutral") {
    if (!["negative", "positive", "neutral"].includes(type)) {
        console.log("Unknown type");
        return;
    }
    var notify_containers = document.getElementsByClassName("status_container");
    var notification = document.createElement("div");
    notification.classList.add("status_" + type);
    notif_number++;
    notification.id = "status_box_" + notif_number;

    var status_box_inner = document.createElement("div");
    status_box_inner.classList.add("status_box_grid");

    var status_icon = document.createElement("div");
    switch (type) {
        case "positive":
            status_icon.innerHTML = '<i class="fas fa-check"></i>';
            break;
        case "neutral":
            status_icon.innerHTML = '<i class="fas fa-info-circle"></i>';
            break;
        case "negative":
            status_icon.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
            break;
    }

    var status_text = document.createElement("div");
    status_text.innerHTML = message;

    var status_timebar = document.createElement("div");
    status_timebar.classList.add("status_timebar");
    var status_timebar_inner = document.createElement("div");
    status_timebar_inner.classList.add("status_timebar_inner");
    status_timebar_inner.style.width = "100%";

    status_box_inner.appendChild(status_icon);
    status_box_inner.appendChild(status_text);
    notification.appendChild(status_box_inner);
    status_timebar.appendChild(status_timebar_inner);
    notification.appendChild(status_timebar);

    notification.addEventListener("click", (event) => {
        status_remove(event.target.parentElement.parentElement.id);
    });

    for (let i = 0; i < notify_containers.length; i++) {
        const container = notify_containers[i];
        container.prepend(notification);
        notif = document.getElementById(notification.id);
        notif.style.transition = "2.5s";
        window.setTimeout(() => {
            notif.style.borderBottomRightRadius = "1rem";
        }, 5);
    }
}

function status_remove(element_id) {
    element = document.getElementById(element_id);
    element.style.transition = "1s";
    element.style.transform = "translateX(10rem)";
    element.style.filter = "opacity(0) blur(5px)";
    window.setTimeout(() => {
        element.remove();
    }, 1000);
}

setInterval(() => {
    var timebars = document.getElementsByClassName("status_timebar_inner");
    for (let i = 0; i < timebars.length; i++) {
        const bar = timebars[i];
        var current_width = bar.style.width;
        if (current_width == undefined) {
            bar.style.width = "100%";
        }
        bar.style.width = (current_width.replace("%", "") - 0.25) + "%";
        if (current_width.replace("%", "") <= 0) {
            status_remove(bar.parentElement.parentElement.id);
        }
    }
}, 15);
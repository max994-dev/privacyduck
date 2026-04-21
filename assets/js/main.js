toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right", // other options: toast-top-right, etc.
    "timeOut": "3000"
};
function activePanel(panel, withPlus = true) {
    panel.style["borderColor"] = "#24A556";
    if (withPlus) panel.querySelector(".icon-plus").style["color"] = "#24A556";
    if (withPlus) panel.querySelector(".icon-minus").style["color"] = "#24A556";
}
function inactivePanel(panel) {
    panel.style["borderColor"] = "rgb(0 0 0 / var(--tw-border-opacity, 1))";
    panel.querySelector(".icon-plus").style["color"] = "rgb(0 0 0 / var(--tw-border-opacity, 1))";
    panel.querySelector(".icon-minus").style["color"] = "rgb(0 0 0 / var(--tw-border-opacity, 1))";
}
function changeActiveCollase(method) {
    const element = document.querySelector("#accordion-collapse");
    if (method === 0) {
        element.style.borderTopColor = "rgb(0 0 0 / var(--tw-border-opacity, 1))"
        element.style.borderBottomColor = "rgb(0 0 0 / var(--tw-border-opacity, 1))"
    }
    else if (method === 3) {
        element.style.borderTopColor = "#24A556";
        element.style.borderBottomColor = "#24A556";
    }
    else if (method === 1) {
        element.style.borderTopColor = "#24A556";
        element.style.borderBottomColor = "rgb(0 0 0 / var(--tw-border-opacity, 1))";
    }
    else if (method === 2) {
        element.style.borderTopColor = "rgb(0 0 0 / var(--tw-border-opacity, 1))";
        element.style.borderBottomColor = "#24A556";
    }
}
function toggleCollapse() {
    let panels = document.querySelectorAll("#accordion-collapse>h1");
    for (let i = 0; i < panels.length; i++) {
        const element = panels[i];
        inactivePanel(panels[i]);
        changeActiveCollase(0);
    }
    for (let i = 0; i < panels.length; i++) {
        const element = panels[i];
        if (element.querySelector("button").getAttribute("aria-expanded") === "true") {
            if (i === 0) changeActiveCollase(1)
            activePanel(panels[i]);
            if (i<panels.length-1) activePanel(panels[i + 1], false);
            else changeActiveCollase(2)
        }
    }
}
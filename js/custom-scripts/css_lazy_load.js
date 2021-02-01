const refs = ["vendor/fontawesome-free/css/all.min.css"];

function loadCSSLazy(refs) {
    const parent = document.getElementById("lazy-css");
    for(const ref of refs){
        const element = document.createElement("link");
        element.href = ref;
        element.rel = "stylesheet";
        element.type = "text/css";
        parent.appendChild(element);
    }
}

loadCSSLazy(refs);
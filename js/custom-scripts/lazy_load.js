class MY_LazyLoader {
  static loadCSS(refs) {
    const parent = document.getElementById("lazy-css");
    for (const ref of refs) {
      const element = document.createElement("link");
      element.href = ref;
      element.rel = "stylesheet";
      element.type = "text/css";
      parent.appendChild(element);
    }
  }

  static _refs = [
    "vendor/fontawesome-free/css/all.min.css",
    "https://fonts.googleapis.com/css?family=Montserrat:400,700",
    "https://fonts.googleapis.com/css?family=Kaushan+Script",
    "https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic",
    "https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700",
    "https://fonts.googleapis.com/css?family=Mukta:700",
  ];
}

MY_LazyLoader.loadCSS(MY_LazyLoader._refs);

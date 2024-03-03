function showBDGExLayer(checkbox, range, layer) {
    let r = document.getElementById(range);
    let l = layers.get(layer);

    if (checkbox.checked == true) {
        l.show = true;
        r.hidden = false;
    } else {
        l.show = false;
        r.hidden = true;
    }
}

function rangeLayer(range, layer) {
    let l = layers.get(layer);
    l.alpha = range.value;
}

// CZML File Upload
let inputFile = document.getElementById("fileUpload")

inputFile.addEventListener('change', function () {
    const file = this.files[0];
    const reader = new FileReader();

    if (file) {
        reader.readAsBinaryString(file);
    }

    reader.addEventListener('load', function () {

        let formData = new FormData();
        formData.append("file", file);
        fetch('upload.php', { method: "POST", body: formData });
        alert('Arquivo carregado com sucesso!');

        viewer.dataSources.add(Cesium.CzmlDataSource.load("upload/document.czml")
        );
    });

});

// Context Menu
let contextMenu = (function () {
    let c = {};
    function t(e) {
        c = e;
        let t = document.createElement("div");
        t.classList.add(c.className || "contextMenu"),
            t.setAttribute("id", c.id || "contextMenuId"),
            o(t, { position: "fixed", display: "none", top: "0px", left: "0px" });
        let i = document.createElement("ul");
        o(i, {
            listStyle: "none",
            padding: "0px",
            margin: "0px",
            display: "flex",
            flexDirection: "column",
        }),
            c.items.forEach((t, n) => {
                let e = document.createElement("li");
                (e.innerHTML = t.template),
                    e.classList.add("contextMenuItem"),
                    o(e, { cursor: "pointer" }),
                    e.addEventListener("click", function (e) {
                        t.onClick(e.target, n);
                    }),
                    i.classList.add("contextMenuList"),
                    i.appendChild(e);
            }),
            t.appendChild(i),
            document.body.appendChild(t);
    }
    function o(t, n) {
        Object.keys(n).forEach((e) => {
            t.style[e] = n[e];
        });
    }
    return (
        (t.prototype.init = function (e) {
            let t = e || document;
            e = c.id || "contextMenuId";
            let n = document.querySelector("#" + e);
            n.addEventListener("mouseleave", function () {
                o(this, { display: "none" });
            }),
                t.addEventListener("contextmenu", function (e) {
                    e.preventDefault(),
                        o(n, {
                            top: e.clientY + "px",
                            left: e.clientX + "px",
                            display: "block",
                        });
                });
        }),
        function (e) {
            return new t(e);
        }
    );
})();

function rightClickScene() {
    contextMenu({
        items: [
            {
                template: "<a href='#'>Adicionar uma peça de manobra aqui</a>",
                onClick:function (item, index) {
                    var modal = document.getElementById("myModal");
                    modal.style.display = "block";
                }
            },
            {
                template: "<a href='#'>Facebook</a>",
                onClick: function (item, index) {
                    console.log("you have clicked on Facebook link !!!!!!")
                }
            },
            {
                template: "<a href='#'>Youtube</a>",
                onClick: function (item, index) {
                    console.log("you have clicked on Youtube link !!!!!!")
                }
            }
        ]
    }).init(document.getElementById("contextMenu"));
}

function rightClicVRDAE() {
    contextMenu({
        items: [
            {
                template: "<a href='#'>Github</a>",
                onClick: function (item, index) {
                    console.log("you have clicked on VRDAE link !!!!!!")
                }
            },
        ]
    }).init(document.getElementById("contextMenu"));
}

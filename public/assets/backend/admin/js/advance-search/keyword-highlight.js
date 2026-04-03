window.addEventListener("load", function () {
    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }
    function highlightText(keyword) {
        if (!keyword) return;

        const regex = new RegExp(`(${keyword})`, "gi");

        const walker = document.createTreeWalker(
            document.body,
            NodeFilter.SHOW_TEXT,
            {
                acceptNode: function (node) {
                    const parent = node.parentNode;
                    if (
                        parent &&
                        parent.nodeName !== "SCRIPT" &&
                        parent.nodeName !== "STYLE" &&
                        !parent.closest("mark") &&
                        node.nodeValue.trim().length > 0
                    ) {
                        return NodeFilter.FILTER_ACCEPT;
                    }
                    return NodeFilter.FILTER_REJECT;
                }
            }
        );

        const nodesToReplace = [];

        while (walker.nextNode()) {
            const node = walker.currentNode;
            if (regex.test(node.nodeValue)) {
                nodesToReplace.push(node);
            }
        }

        nodesToReplace.forEach(node => {
            const span = document.createElement("span");
            span.innerHTML = node.nodeValue.replace(regex, '<mark>$1</mark>');
            node.parentNode.replaceChild(span, node);
        });
    }

    const keyword = getQueryParam("keyword");
    if (keyword) {
        highlightText(keyword);
    }
});


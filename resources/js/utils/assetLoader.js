// utils/assetLoader.js
export function loadAssets(role) {
    const head = document.head;

    // Remove old role-based assets
    const oldLinks = head.querySelectorAll('link[data-role], script[data-role]');
    oldLinks.forEach((link) => link.remove());

    // Define role-based styles and scripts
    const assets = {
        admin: {
            css: [],
            js: [],
        },
        user: {
            css: [],
            js: [],
        },
        guest: {
            css: [],
            js: [],
        },
    };

    const selectedAssets = assets[role] || assets.guest;

    // Add CSS
    selectedAssets.css.forEach((href) => {
        const link = document.createElement("link");
        link.rel = "stylesheet";
        link.href = href;
        link.setAttribute("data-role", role);
        head.appendChild(link);
    });

    // Add JS
    selectedAssets.js.forEach((src) => {
        const script = document.createElement("script");
        script.src = src;
        script.setAttribute("data-role", role);
        script.defer = true; // Ensure scripts don't block rendering
        head.appendChild(script);
    });
}

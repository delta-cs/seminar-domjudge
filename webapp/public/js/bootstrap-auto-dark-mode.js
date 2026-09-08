/**
 * Based on:
 *
 * Author and copyright: Stefan Haack (https://shaack.com)
 * Repository: https://github.com/shaack/bootstrap-auto-dark-mode
 * License: MIT, see file 'LICENSE'
 */

window.updateTheme = function(theme) {
    theme = theme || localStorage.getItem("theme") || "auto";

    if (theme !== "dark" && theme !== "light") {
        theme = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
    }

    localStorage.setItem("theme", theme);
    document.querySelector("html").setAttribute("data-bs-theme", theme);

    // Monaco replaced ACE in DOMjudge 9.0: themes are global, not per instance, and
    // an editor instance has no setTheme() at all. Only follow the page theme where
    // the user has not chosen an editor theme themselves - the jury pages have their
    // own picker ([data-editor-themes]) and a stored preference to respect.
    if (typeof window.monaco !== "undefined"
        && document.querySelector("[data-editor-themes]") === null
        && localStorage.getItem("domjudge_editor_theme") === null) {
        window.monaco.editor.setTheme(theme === "dark" ? "vs-dark" : "vs");
    }
}

;(function () {
    if (document.querySelector("html").getAttribute("data-bs-theme") === 'auto') {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', window.updateTheme);
        window.updateTheme();
    }
})();

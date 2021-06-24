let darkMode = document.getElementById("darkMode");
let themeIcon = document.getElementById("themeIcon");

/**
 * Function that checks which theme is already selected and switches to the other
 * when the button is clicked.
 */
window.addEventListener("load", function () {
    initTheme();
    darkMode.addEventListener("click", function (){
        if (themeIcon.textContent === "lightbulb"){
            themeIcon.textContent = "nightlight";
            document.body.setAttribute("data-theme", "dark");
            localStorage.setItem("darkSwitch", "dark");
        }else{
            themeIcon.textContent = "lightbulb";
            document.body.removeAttribute("data-theme");
            localStorage.removeItem("darkSwitch");
        }
    });
});

/**
 * Verifies which theme is selected on local storage.
 *
 * @return {void}
 */
function initTheme() {
    var darkThemeSelected =
        localStorage.getItem("darkSwitch") !== null &&
        localStorage.getItem("darkSwitch") === "dark";
    if (darkThemeSelected){
        themeIcon.textContent = "nightlight";
    } else{
        themeIcon.textContent = "lightbulb";
    }
    darkThemeSelected
        ? document.body.setAttribute("data-theme", "dark")
        : document.body.removeAttribute("data-theme");
}

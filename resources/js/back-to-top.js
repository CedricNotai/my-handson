/**
 * Back to top button
*/

window.addEventListener("DOMContentLoaded", () => {
  initBackToTopButton();
});
  
function initBackToTopButton() {
  const button = document.querySelector("#scroll-to-top");
  const topSection = document.querySelector("#top");
  const bottomSection = document.querySelector("#js-bottom");
  
  window.addEventListener("scroll", () => {
    const topScrollTreshold = topSection.getBoundingClientRect().top + window.pageYOffset < window.pageYOffset; 
    const bottomScrollTreshold = button.getBoundingClientRect().top + window.pageYOffset > bottomSection.getBoundingClientRect().top + window.pageYOffset;
    
    // set the visibility of the button based on the scroll position
    if (topScrollTreshold) {
      button.classList.add("visible");
    } else if (button.classList.contains("visible")) {
      button.classList.remove("visible");
    }
    
    if (bottomScrollTreshold) {
      button.classList.remove("visible");
    }
  });
}
  
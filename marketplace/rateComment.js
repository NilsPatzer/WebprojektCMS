const modal = document.querySelector("#modal");
const stars = document.querySelector("#stars");
const ratingInput = document.querySelector("#inputRating");
const fillStar = stars.children[0];
const emptyStar = stars.children[1];
const inputTitle = document.querySelector("#inputTitle");

const DISPLAY = fillStar.style.display;

stars.removeChild(fillStar);
stars.removeChild(emptyStar);

const even = (x) => x % 2 == 0;
const odd = (x) => x % 2 == 1;

const updateStars = (rating) => {
  for (let i = 0; i < 10; i++) {
    if (i < rating * 2) {
      // even rating show filled stars
      stars.children[i].style.display = odd(i) ? DISPLAY : "none";
    } else {
      //odd rating show empty stars
      stars.children[i].style.display = even(i) ? DISPLAY : "none";
    }
  }
};

//initialize stars
for (let i = 0; i < 10; i++) {
  const star = odd(i) ? fillStar.cloneNode(true) : emptyStar.cloneNode(true);
  stars.insertBefore(star, ratingInput);
}

updateStars(0);

modal.addEventListener("shown.bs.modal", () => {
  inputTitle.focus();

  for (let i = 0; i < 10; i++) {
    const star = stars.children[i];
    const rating = Math.ceil((i + 1) / 2);
    star.addEventListener("mouseover", () => {
      updateStars(rating);
    });
    star.addEventListener("mouseout", () => {
      updateStars(ratingInput.value);
    });
    star.addEventListener("click", () => {
      ratingInput.value = rating;
    });
  }
});

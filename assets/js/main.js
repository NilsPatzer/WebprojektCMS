var slides = document.querySelector(".slider-items").children;
var nextSlide = document.querySelector(".right-slide");
var prevSlide = document.querySelector(".left-slide");
var totalSlides = slides.length;
var index = 0;

nextSlide.onclick = function () {
	next("next");
};
prevSlide.onclick = function () {
	next("prev");
};

function next(direction) {
	if (direction == "next") {
		index++;
		if (index == totalSlides) {
			index = 0;
		}
	} else {
		if (index == 0) {
			index = totalSlides - 1;
		} else {
			index--;
		}
	}

	for (i = 0; i < slides.length; i++) {
		slides[i].classList.remove("active");
	}
	slides[index].classList.add("active");
}
/*  ImageGallery */
function imageGallery() {
	const highlight = document.querySelector(".gallery-hightlight");
	const previews = document.querySelectorAll(".gallery-preview img");

	previews.forEach((preview) => {
		preview.addEventListener("click", function () {
			const smallSrc = this.src;
			const bigSrc = smallSrc.replace("small", "big");
			previews.forEach((preview) =>
				preview.classList.remove("gallery-active")
			);
			highlight.src = bigSrc;
			preview.classList.add("gallery-active");
		});
	});
}

imageGallery();

/* beginn Formularvalidierung */
const name = document.getElementById('name')
const password = document.getElementById('password')
const form = document.getElementById('form')
const errorElement = document.getElementById('error')

form.addEventListener('submit', (e) => {
  let messages = []
  if (name.value === '' || name.value == null) {
    messages.push('Name is required')
  }

  if (password.value.length <= 6) {
    messages.push('Password must be longer than 6 characters')
  }

  if (password.value.length >= 20) {
    messages.push('Password must be less than 20 characters')
  }

  if (password.value === 'password') {
    messages.push('Password cannot be password')
  }

  if (messages.length > 0) {
    e.preventDefault()
    errorElement.innerText = messages.join(', ')
  }
})
// https://github.com/rafaavc/simple-slider
class SimpleSlider {
	constructor(elementId, interval = 3000, firstSlide = 0) {
		this.dir = {
			RIGHT: 0,
			LEFT: 1
		}

		this.interval = {
			dur: interval,
			obj: null
		}

		this.sliderElement = document.getElementById(elementId);
		if (!this.sliderElement) console.log("The slider was not found!");
		//this.sliderElement.style.height = height;

		this.currentSlide = firstSlide;

		this.indexChildren();

		this.generateNav();
	}

	indexChildren() {
		this.children = [];

		const childElements = this.sliderElement.getElementsByClassName('ss-child');
		let counter = 0;
		for (const c of childElements) {
			let childObj = {
				id: counter,
				element: c,
				appear: function (slider, dir) {
					this.element.style.left = "-100%";
					this.element.classList.remove('ss-disappearToLeft');
					this.element.classList.remove('ss-disappearToRight');
					if (dir === slider.dir.RIGHT) {
						this.element.classList.add('ss-appearFromRight');
					} else {
						this.element.classList.add('ss-appearFromLeft');
					}
				},
				disappear: function (slider, dir) {
					this.element.style.left = "0";
					this.element.classList.remove('ss-appearFromRight');
					this.element.classList.remove('ss-appearFromLeft');
					if (dir === slider.dir.RIGHT) {
						this.element.classList.add('ss-disappearToLeft');
					} else {
						this.element.classList.add('ss-disappearToRight');
					}
				}
			}

			if (counter === this.currentSlide) childObj.element.style.left = "0";
			counter++;

			this.children.push(childObj);
		}
	}

	generateNav() {
		const navElements = this.sliderElement.getElementsByClassName('ss-nav');
		if (!navElements.length) this.nav = false;

		for (const child of this.children) {
			let navButton = document.createElement("button");

			navButton.onclick = (function () { this.goTo(child.id); }).bind(this);

			if (child.element.getAttribute('buttonText')) {
				navButton.appendChild(document.createTextNode(child.element.getAttribute('buttonText')));
			}

			child.navButton = navButton;
			if (child.id === this.currentSlide) child.navButton.classList.add('active');

			for (const nav of navElements) {
				nav.appendChild(navButton);
			}
		}

		const rightArrowElements = this.sliderElement.getElementsByClassName('ss-rightArrow');
		if (rightArrowElements.length) {
			for (const ra of rightArrowElements) {
				ra.onclick = (function () { this.goToNext(); }).bind(this);
			}
		}
		const leftArrowElements = this.sliderElement.getElementsByClassName('ss-leftArrow');
		if (leftArrowElements.length) {
			for (const la of leftArrowElements) {
				la.onclick = (function () { this.goToPrev(); }).bind(this);
			}
		}
	}

	updateSlide(changes, direction) {
		if (this.children.length <= 1) return;
		this.children[changes.disappear].disappear(this, direction);
		this.children[changes.disappear].navButton.classList.remove('active');
		this.children[changes.appear].appear(this, direction);
		this.children[changes.appear].navButton.classList.add('active');
	}

	setCurrentSlide(n) {
		const res = {
			disappear: this.currentSlide
		};
		this.currentSlide = n % this.children.length;
		res.appear = this.currentSlide;
		return res;
	}

	incrementCurrentSlide() {
		return this.setCurrentSlide(this.currentSlide + 1);
	}

	decrementCurrentSlide() {
		return this.setCurrentSlide(this.currentSlide === 0 ? this.children.length - 1 : this.currentSlide - 1);
	}

	goToNext() {
		this.pause();
		this.updateSlide(this.incrementCurrentSlide(), this.dir.RIGHT);
		this.start();
	}
	goToPrev() {
		this.pause();
		this.updateSlide(this.decrementCurrentSlide(), this.dir.LEFT);
		this.start();
	}

	goTo(id) {
		if (id === this.currentSlide) return;
		this.pause();
		const dir = id > this.currentSlide ? this.dir.RIGHT : this.dir.LEFT;
		this.updateSlide(this.setCurrentSlide(id), dir);
		this.start();
	}

	start() {
		this.interval.obj = setInterval(this.goToNext.bind(this), this.interval.dur);
	}

	pause() {
		clearInterval(this.interval.obj);
	}
}

export default SimpleSlider;

import Splide from '@splidejs/splide';
import './_categorizeProducts';
import '@fortawesome/fontawesome-free';

const spliceCont = document.querySelectorAll('.splide');
class General {
	constructor() {
		this.testVariable = 'script working';
		this.init();
	}

	init() {
		// for tests purposes only
		console.log(this.testVariable);
		function addSplide() {
			return new Splide('.splide', { type: 'loop', arrows: false, autoplay: true }).mount();
		}
		if (spliceCont[0]) {
			addSplide();
		}
	}
}


export default General;

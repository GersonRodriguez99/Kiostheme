import General from './_generalScripts';


const App = {

	/**
	 * App.init
	 */
	init() {
		function initGeneral() {
			return new General();
		}
		initGeneral();
	}

};

document.addEventListener('DOMContentLoaded', () => {
	App.init();
});


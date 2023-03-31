const element = document.querySelectorAll('.list-group-item');
document.addEventListener('DOMContentLoaded', () => {
	element[0].classList.add('active');
});
element.forEach(ele => {
	ele.addEventListener('click', () => {
		for (let index = 0; index < element.length; index += 1) {
			element[index].classList.remove('active');
		}
		ele.classList.toggle('active');
	});
});

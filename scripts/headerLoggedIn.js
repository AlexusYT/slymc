


function actionHeaderOnClick(action) {
	let header;
	if (action === 'login') header = document.getElementById("headerRectLogin");
	if (action === 'register') header = document.getElementById("headerRectReg");
	header.classList.remove("disabled", 'animate__animated', 'animate__flipOutX');
	header.classList.add('animate__animated', 'animate__flipInX', "animate__fast");
}

function actionButtonBack(action) {
	let header;
	if (action === 'login') header = document.getElementById("headerRectLogin");
	if (action === 'register') header = document.getElementById("headerRectReg");
	header.classList.add('animate__animated', 'animate__flipOutX');
	header.classList.remove('animate__animated', 'animate__flipInX');
	header.onanimationend = function (ev){
		if(ev.animationName === "flipInX") return;
		header.classList.add('disabled');
	};
}
function getError() {
	const error = document.getElementById("errorButton");
	error.style.display = 'flex';
	error.classList.remove('animate__animated', 'animate__fadeOutUp');
	error.classList.add('animate__animated', 'animate__shakeX');
}
function closeError() {
	const error = document.getElementById("errorButton");
	error.classList.remove('animate__animated', 'animate__shakeX');
	error.classList.add('animate__animated', 'animate__fadeOutUp');
	error.onanimationend = function (ev){
		if(ev.animationName !== "fadeOutUp") return;
		error.style.display = 'none';
	};

}
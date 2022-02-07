
function postXml(address, fields){
	let data = "", delim = "";
	for (const key of Object.keys(fields)) {
		data+=delim+key+"="+fields[key];
		delim = "&";
	}
	const xhr = new XMLHttpRequest();
	xhr.open("POST", address, true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.responseType = 'document';
	xhr.send(data);
	return xhr;
}
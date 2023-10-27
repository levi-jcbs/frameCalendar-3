function load_page(page, tab, subtab, query) {
	if (page != null) { document.getElementById("page").value = page; }
	if (tab != null) { document.getElementById("tab").value = tab; }
	if (subtab != null) { document.getElementById("subtab").value = subtab; }
	if (query != null) { document.getElementById("query").value = JSON.stringify(query); }

	document.framecalendar_form.submit();
}

function notification_fadeout(element) {
	element.parentNode.classList.remove("defaultanimation");
	element.parentNode.offsetHeight;
	element.parentNode.classList.add("fadeout");
}

function api_request(call) {
	switch (call) {
		case 'eventlists:create_eventlist':
			var target = "eventlists";
			var action = "create";
			var object = "eventlist";
			var options = { "o_name": document.getElementById("create_eventlist_name").value };
			break;

		default:
			return false;
			break;
	}

	var password = document.getElementById("memory_password").value;

	var url_options = "";
	Object.entries(options).forEach(([option, value]) => {
		url_options += "&" + encodeURIComponent(option) + "=" + encodeURIComponent(value);
	});

	fetch("api/?password=" + encodeURIComponent(password) + "&target=" + encodeURIComponent(target) + "&action=" + encodeURIComponent(action) + "&object=" + encodeURIComponent(object) + url_options)
		.then(response => response.text())
		.then(data => {
			apiresponse = JSON.parse(data);

			if (notify_from_apiresponse(apiresponse)) {
				setTimeout(redirect_from_apiresponse, 1000, apiresponse);
			} else {
				redirect_from_apiresponse(apiresponse);
			}
		});
}

function notify_from_apiresponse(apiresponse) {
	var notification_type;
	var notification_description;

	if (apiresponse.status != "success" && apiresponse.status != "neutral" && apiresponse.status != "error") {
		return false;
	}

	if (!(apiresponse.description != "")) {
		return false;
	}

	if (apiresponse.status == "success") {
		notification_type = "positive";
	} else if (apiresponse.status == "error") {
		notification_type = "negative";
	} else if (apiresponse.status == "neutral") {
		notification_type = "neutral";
	}

	notification_description = apiresponse.description;

	notify(notification_type, notification_description);
	return true;
}

function notify(type, description) {
	var template = document.getElementById("notification_template");
	var clone = template.content.cloneNode(true);
	var container = document.getElementById("notifications");

	clone.firstElementChild.classList.add(type);
	clone.firstElementChild.firstElementChild.innerText = description;
	container.appendChild(clone);
}

function redirect_from_apiresponse(apiresponse) {
	if (Array.isArray(apiresponse.target)) {
		load_page(apiresponse.target[0], apiresponse.target[1], apiresponse.target[2], apiresponse.target[3]);
	}
}
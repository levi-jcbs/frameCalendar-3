function load_page(page, tab, screen, options) {
	if (page != null) { document.getElementById("memory_page").value = page; }
	if (tab != null) { document.getElementById("memory_tab").value = tab; }
	if (screen != null) { document.getElementById("memory_screen").value = screen; }
	if (options != null) { document.getElementById("memory_options").value = JSON.stringify(options); }

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
			var options = { "name": document.getElementById("create_eventlist_name").value };
			break;

		default:
			return false;
			break;
	}

	call_parameters = call.split(/:|_/);

	var password = document.getElementById("memory_password").value;
	var group = call_parameters[0];
	var action = call_parameters[1];
	var object = call_parameters[2];

	var url_options = "";
	Object.entries(options).forEach(([option, value]) => {
		url_options += "&o_" + encodeURIComponent(option) + "=" + encodeURIComponent(value);
	});

	fetch("api/?password=" + encodeURIComponent(password) + "&group=" + encodeURIComponent(group) + "&action=" + encodeURIComponent(action) + "&object=" + encodeURIComponent(object) + url_options)
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
	if (typeof apiresponse.target === 'object' && apiresponse.target !== null) {
		load_page(apiresponse.target.page, apiresponse.target.tab, apiresponse.target.screen, apiresponse.target.options);
	}
}
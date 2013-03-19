window.addEvent('domready', function() {
	var modal = $('modal');
	var popup = $('popup');
	var status = $('status');

	var txtEmail = $('txtEmail');
	var txtName = $('txtName');
	var txtMessage = $('txtMessage');
	
	var btnSend = $('btnSend');
	var btnClose = $('btnClose');
	var btnMail = $('btnMail');
	
	var activeElem = false;
	
	
	function positionElem(elem) {
		var d = elem.getSize();
		var w = window.getSize();
		
		elem.setStyle('left', ((w.x - d.x) / 2));
		elem.setStyle('top', ((w.y - d.y) / 2));
	}
	function openElem(elem, opac, position) {
		var fx = new Fx.Tween(elem, {
			'onStart': function() {
				elem.setStyle('display', 'block');
				if (position != null && position == true)
					positionElem(elem);
			}
		});
		elem.setStyle('opacity', 0);
		fx.start('opacity', opac);
	}
	function closeElem(elem) {
		var fx = new Fx.Tween(elem, {
			'onComplete': function() {
				elem.setStyle('display', 'none');
			}
		});
		fx.start('opacity', 0);
	}
	
	btnMail.addEvent('click', function() {
		openElem(modal, 0.8);
		openElem(popup, 1, 1);
		activeElem = popup;
	});
	btnClose.addEvent('click', function() {
		closeElem(modal);
		closeElem(popup);
		activeElem = false;
	});
	
	function closeForm() {
		txtEmail.set('value');
		txtName.set('value');
		txtMessage.set('value');
		txtEmail.erase('disabled');
		txtName.erase('disabled');
		txtMessage.erase('disabled');
		status.set('class', '');
		closeElem(modal);
		closeElem(popup);
		activeElem = false;
	}
	
	btnSend.addEvent('click', function() {
		var req = new Request({
			'url': 'index.php',
			'data': {
				'email': txtEmail.get('value'),
				'name': txtName.get('value'),
				'message': txtMessage.get('value')
			},
			'onRequest': function() {
				status.set('class', 'loader');
				status.set('html', 'Sending form...');
				txtEmail.set('disabled', true);
				txtName.set('disabled', true);
				txtMessage.set('disabled', true);
			},
			'onFailure': function() {
				status.set('class', 'msg_err');
				status.set('html', 'Connection error!');
			},
			'onSuccess': function(res) {
				if (res == 'TRUE') {
					status.set('class', 'msg_ok');
					status.set('html', 'Message sent!');
					closeForm.delay(100);
				} else {
					status.set('class', 'msg_err');				
					status.set('html', 'Could not send message. Sorry!');
					txtEmail.erase('disabled');
					txtName.erase('disabled');
					txtMessage.erase('disabled');
				}
			}
		});
		req.send();
	});
	
	window.addEvent('resize', function() {
		if (activeElem)
			positionElem(activeElem);
	});
});

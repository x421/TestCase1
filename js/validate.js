	var name1, soname, ots, email, text, button, msg;
	
	nameError = 0b1;
	sonameError = 0b10;
	otsError = 0b100;
	emailError = 0b1000;
	
	var dataOut = -4;
	var mail = "testmail@mail1111.ru"
	regNames = new RegExp("^[А-Яа-яё]+$", "msiu");
	
	name1 = document.getElementById("name1");
	soname = document.getElementById("soname");
	ots = document.getElementById("ots");
	email = document.getElementById("email");
	text = document.getElementById("text");
	button = document.getElementById("send");
	msg = document.getElementById("msg");
	document.forms[0].onsubmit = send;
	button.onclick = send;
	
	function validateEmail(email) {
	  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	  return re.test(email);
	}
	
	function sendMail()
	{
		document.location.href = "mailto:"+ mail +"?subject=обратная связь"
		+ "&body=ФИО: "+soname.value.trim()+" "+name1.value.trim()+" "+
		ots.value.trim()+"<br>"+
		"email: "+email.value.trim()+"<br>"+
		"Сообщение: "+text.value.trim();
	}
	
	function parseError1(error)
	{
		if((error & nameError) != 0) // 1000 - ошибка в имени
			name1.classList.add("is-invalid");
		
		if((error & sonameError) != 0) // 1 - ошибка в емаил
			soname.classList.add("is-invalid");
		
		if((error & otsError) != 0) // ошибка в отчестве
			ots.classList.add("is-invalid");
		
		if((error & emailError) != 0) // ошибка в фамилии
			email.classList.add("is-invalid");
	}
	
	function send(button)
	{
		
		error = 0;
		if(regNames.exec(name1.value) == null)
			error += 1;
		if(regNames.exec(soname.value) == null)
			error += 2;
		if(regNames.exec(ots.value) == null)
			error += 4;
		if(validateEmail(email.value) == false)
			error += 8;
		
		name1.classList.remove("is-invalid");
		email.classList.remove("is-invalid");
		ots.classList.remove("is-invalid");
		soname.classList.remove("is-invalid");
		
		if(error != 0)
		{
			msg.innerText="Заполните выделенные поля корректно!";
			parseError1(error);
			return false;
		}
		
		var answer = 
		$.ajax({
			type: "POST",
			url: "./php/writeData.php",
			async: false,
			data: "name="+name1.value+"&soname="+soname.value+"&ots="+ots.value+"&email="+email.value+"&text="+text.value+"",
		}).responseText;
		
		var ans = 1;
		
		switch(parseInt(answer))
		{
			case 0: 
				sendMail();
				msg.innerText="Ваш запрос успешно отправлен!";
				break;
			case -1:
				msg.innerText="С этого email уже отправлен запрос!";
				ans = 0;
				break;
			case -3:
				msg.innerText="Ошибка базы данных, попробуйте позже";
				ans = 0;
				break;
			case -4:
				msg.innerText="Ваше обращение записано, но на почту не отправлено";
				ans = 0;
				break;
			default:
				msg.innerText="Заполните выделенные поля корректно!";
				parseError1(parseInt(answer));
				ans = 0;
				break;
		};
		
		if(ans == 0)
			return false;
		else
			return true;
	}
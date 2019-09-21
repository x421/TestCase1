	var name1, soname, ots, email, text, button, msg;
	var dataOut = -4;
	var mail = "testmail@mail1111.ru"
	function sendMail()
	{
		document.location.href = "mailto:"+ mail +"?subject=обратная связь"
		+ "&body=ФИО: "+soname.value.trim()+" "+name1.value.trim()+" "+
		ots.value.trim()+"<br>"+
		"email: "+email.value.trim()+"<br>"+
		"Сообщение: "+text.value.trim();
	}
	
	function send(button)
	{
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
			case -2:
				msg.innerText="Заполните корректно все поля!";
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
		};
		
		if(ans == 0)
			return false;
		else
			return true;
	}
	
	window.onload = function()
	{	
		name1 = document.getElementById("name1");
		soname = document.getElementById("soname");
		ots = document.getElementById("ots");
		email = document.getElementById("email");
		text = document.getElementById("text");
		button = document.getElementById("send");
		msg = document.getElementById("msg");
		//button.onclick = send;
		document.forms[0].onsubmit = send;
	}
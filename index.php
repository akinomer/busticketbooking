<?php
	include('vt.php');

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	//Load Composer's autoloader
	require 'vendor/autoload.php';

	if($_GET['type']=='search'){
		
		$data = [
			'ticket_props'	=>	$_POST['flight_prop']
		];
		$db->table('flight')->insert($data);
		echo $db->lastInsertId();
		exit;
	}

	if($_GET['type']=='ktrlcontrol'){
		$ktrlcontrol = $db->customQuery("Select * From flight Where id=".$_GET['id'])->getRow();
		echo json_encode($ktrlcontrol);
		exit;
	}

	if($_GET['type']=='book_order'){

		$mail = new PHPMailer(true);

		try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'mail.airbnbbook.net';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'info@airbnbbook.net';                     //SMTP username
            $mail->Password   = 'Huso123123*';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('info@airbnbbook.net', 'Flight Booking');
            $mail->addAddress('info@airbnbbook.net', 'Flight Booking');     //Add a recipient
            //Attachments

            //Content
            
            $body .= "Ad Soyad:".$_POST['name'];
            $body .= "<br>";
            $body .= "E-Posta:".$_POST['email'];
            $body .= "<br>";
            $body .= "Mobil:".$_POST['phone'];
            $body .= "<br>";
            $body .= "Extra Not:".$_POST['message'];
			$body .= "<br><br><a target='_blank' href='https://www.airbnbbook.net/flights/bookinfo.php?id=".$_POST['flight_bilgi_id']."'>Siparis bilgileri icin tiklayiniz</a> https://www.airbnbbook.net/flights/bookinfo.php?id=".$_POST['flight_bilgi_id']."<br>Siparis bilgileri icin tiklayiniz";
            
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Siparis Bilgisi';
            $mail->Body    = $body;

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        } 

		$db->table('orders')->insert($_POST);
		exit;
	}
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Booking Flight Ticket</title>
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
	<link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" />
	<link type="text/css" rel="stylesheet" href="css/style.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<script src="js/magicsuggest.js"></script>
	<link href="css/magicsuggest.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<style>

		.table{
			background-color: #fff;
		}
		span.price{
			color: #000;
			font-weight: bold;
			font-size: 18px;
		}
		.searchDiv {
		width:405px;
		}
		.fromDiv {
		display:none;
		float:right;
		width:302px; 
		max-height:200px;
		overflow:auto;
		border:1px solid #0099FF;
		padding:5px;
		z-index:999;
		background-color: white;
		position: absolute;
		}

		.toDiv {
		display:none;
		float:right;
		width:302px; 
		max-height:200px;
		overflow:auto;
		border:1px solid #0099FF;
		padding:5px;
		z-index:999;
		background-color: white;
		position: absolute;
		}
		
		ul {
		margin:0;
		padding:0;
		}
		ul li {
		list-style:none;
		clear:both;
		width:100%;
		padding: 5px 0px 35px;
		border-bottom:1px solid #ccc;
		}
		ul li:hover, ul li:hover > a {
		background-color:#0099FF;
		color:#fff;
		cursor:pointer;
		}
		ul img {
		float:left;
		margin:0 5px;
		padding:3px;
		border:1px solid #ccc;
		border-radius:5px;
		}
		ul a {
		text-decoration:none;
		color:#666;
		display:inline-table;
		}
	</style>
</head>

<body>
	<div id="booking" class="section">
		<div class="section-center">
			<div class="container">
				<div class="row">
					<div class="col-md-4">
						<div class="booking-cta">
							<h1>Book your flight today</h1>
							<!--<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cupiditate laboriosam numquam at</p>-->
						</div>
					</div>
					<div class="col-md-7 col-md-offset-1">
						<div class="booking-form">
							<form id="search">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<span class="form-label">Kalkis Noktasi</span>
											<input class="form-control" id="from" name='from' type="text" autocomplete='off' placeholder="Sehir Seciniz" required>
											<div class="fromDiv"></div>
											<input type="hidden" name="from_id" id="from_id" value="">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<span class="form-label">Varis Noktasi</span>
											<input class="form-control" id='to' name='to' type="text" autocomplete='off' placeholder="Sehir Seciniz" required>
											<div class="toDiv"></div>
											<input type="hidden" name="to_id" id="to_id" value="">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<span class="form-label">Yolculuk Tarihi</span>
											<input class="form-control" id='departing' name='departing' type="date" required>
										</div>
									</div>
								</div>
								<div class="form-btn">
									<button type='button' class="submit-btn">Show flights</button>
								</div>
							</form>
						</div>
						<div class='col-md-12 flight_list'>
							<div style='overflow: scroll; height: 500px;'>
								
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
    <script>

Date.prototype.toTurkishFormatDate = function(format) {
    var date = this,
            day = date.getDate(),
            weekDay = date.getDay(),
            month = date.getMonth(),
            year = date.getFullYear(),
            hours = date.getHours(),
            minutes = date.getMinutes(),
            seconds = date.getSeconds();

    var monthNames = new Array("Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran", "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık");
    var dayNames = new Array("Pazar", "Pazartesi", "Salı", "Çarşamba", "Perşembe", "Cuma", "Cumartesi");

    if (!format) {
        format = "dd.MM.yyyy";
    }


    //format = format.replace("mm", month.toString().padStart(2, "0"));
    format = format.replace("MM", monthNames[month]);

    if (format.indexOf("yyyy") > -1) {
        format = format.replace("yyyy", year.toString());
    } else if (format.indexOf("yy") > -1) {
        format = format.replace("yy", year.toString().substr(2, 2));
    }

    format = format.replace("dd", day.toString().padStart(2, "0"));

    format = format.replace("DD", dayNames[weekDay]);

    if (format.indexOf("HH") > -1) {
        format = format.replace("HH", hours.toString().replace(/^(\d)$/, '0$1'));
    }

    if (format.indexOf("hh") > -1) {
        if (hours > 12) {
            hours -= 12;
        }

        if (hours === 0) {
            hours = 12;
        }
        format = format.replace("hh", hours.toString().replace(/^(\d)$/, '0$1'));
    }

    if (format.indexOf("ii") > -1) {
        format = format.replace("ii", minutes.toString().replace(/^(\d)$/, '0$1'));
    }

    if (format.indexOf("ss") > -1) {
        format = format.replace("ss", seconds.toString().replace(/^(\d)$/, '0$1'));
    }

    return format;
};

		function getQueryVariable(qs, variable) {
			var query = qs;
			var vars = query.split('&');
			for (var i = 0; i < vars.length; i++) {
				var pair = vars[i].split('=');
				if (decodeURIComponent(pair[0]) == variable) {
					return decodeURIComponent(pair[1]);
				}
			}
			console.log('Query variable %s not found', variable);
		}

		function sendBookOrder(){

			if($('#name').val()==''){
				alert('Please enter your name');
			}else if($('#email').val()==''){
				alert('Please enter your email');
			}else if($('#phone').val()==''){
				alert('Please enter your phone');
			}else{
				var flight_bilgi_id = $('#flight_bilgi').val();
				var index = $('#index').val();
				var name = $('#name').val();
				var email = $('#email').val();
				var phone = $('#phone').val();
				var message = $('#message').val();

				$.post("index.php?type=book_order", {
						flight_bilgi_id: flight_bilgi_id,
						indexy: index,
						name: name,
						email: email,
						phone: phone,
						message: message
					}, function(result){
					console.log(result);
					alert('We have got your order. We will contact you soon.');
					location.reload();
				});
			}
		}

		function Order(id){ //, flight_id
			$('#myModal').modal('show');
			var icerik = $('.tablo-'+id).html();
			$('.modal-body table').html(icerik);
			var fiyat = $('.modal-body table tr td:eq(5) button').html()
			$('.modal-body table tr td:eq(5)').remove();
			$('.modal-body p.fiyat').html(fiyat);
			//$('#flight_bilgi').val(flight_id);
			$('#index').val(id);
		}

		function getFormData($form){
			var unindexed_array = $form.serializeArray();
			var indexed_array = {};

			$.map(unindexed_array, function(n, i){
				indexed_array[n['name']] = n['value'];
			});

			return indexed_array;
		}

        $(function() {

			$('.submit-btn').click(function() {
				$(this).prop('disabled', true);

				$(this).html('<i class="fa fa-spinner fa-spin"></i> Seyahatler Yukleniyor, Lutfen Bekleyiniz...');

				var gidis = getQueryVariable($('#search').serialize(), 'departing');
				var gun = gidis.split('-')[2];
				var ay = gidis.split('-')[1];
				var yil = gidis.split('-')[0];
				var gidis_tarih = gun+'.'+ay+'.'+yil;
				var from = getQueryVariable($('#search').serialize(), 'from_id');
				var to = getQueryVariable($('#search').serialize(), 'to_id');
				//https://www.enuygun.com/otobus-bileti/async-result/antalya-otogari-ankara-otogari/?gidis=27.12.2021
				var qs = from+'-'+to+'/?gidis='+gidis_tarih;
				$.get('fetchinfo.php?qs='+qs, function(data){
					var cisin = JSON.parse(data)['result']['journeys'];
					var html = '';
					console.log(cisin);
					for(var i=0; i<cisin.length; i++){
						var simdikiTarih = new Date(cisin[i]['segments'][0]['departure_date'].replace(' ','T'));

						var prifiyat = parseFloat(cisin[i]['price']['total']) + (parseFloat(cisin[i]['price']['total']) * 0,10);

						//cisin[i]['fiyat'];
						html += `<table class='table tablo-`+i+`'>
									<tbody>
										<tr>
											<td>
												<img src='`+cisin[i]['segments'][0]['company']['brand']+`'>
												<br><small>`+cisin[i]['segments'][0]['origin']+` > `+cisin[i]['segments'][0]['destination']+`</small>
											</td>
											<td>
												`+cisin[i]['segments'][0]['bus_info']['seat_type']+`
											</td>
											<td>
												`+simdikiTarih.toTurkishFormatDate("dd MM DD yyyy HH:ii")+`<br>
												Ort: `+cisin[i]['segments'][0]['journey_time_diff']['approximate']+` sa
											</td>
											<td class='text-center'>
												<span class='price'>`+prifiyat+`</span> <sup><small>,00 TL</small></sup><br>
												<button class='btn btn-success btn-sm' onclick='Order(`+i+`)'>SATIN AL</button>
											</td>
										</tr>
									</tbody>
								</table>`;
					}
					$('.booking-form').hide();
					$('.flight_list').show();
					$('.flight_list div').html(html);
				});
			});

			var delay = (function(){
				var timer = 0;
				return function(callback, ms){
					clearTimeout (timer);
					timer = setTimeout(callback, ms);
				};
			})();

            $("#from").keyup(function(){
				var resp='<ul>';
				setTimeout(() => {
					$.get("fetch.php?term="+$(this).val(), function(data){
						var data = JSON.parse(data);
						console.log(data);
						if(data.length > 0) {
							for(var i = 0; i < data.length; i++){
								resp += `<li id="fromli-`+i+`" onclick="getFromData('`+data[i].slug+`|`+data[i].name+`')" class="text-center"><h4>`+data[i].name+`</h4><span><b>`+data[i].type+`</b></span></li>`;
							}
							resp += '</ul>';
							$(".fromDiv").css("width", $("#from").width()+' px');
							$(".fromDiv").html(resp).show();  
						} else {
							$(".fromDiv").html("").hide();
						}
					});
				}, 200);
			});

			$("#to").keyup(function(){
				var resp='<ul>';
				setTimeout(() => {
					$.get("fetch.php?term="+$(this).val(), function(data){
						var data = JSON.parse(data);
						console.log(data);
						if(data.length > 0) {
							for(var i = 0; i < data.length; i++){
								resp += `<li id="toli-`+i+`" onclick="getToData('`+data[i].slug+`|`+data[i].name+`')" class="text-center"><h4>`+data[i].name+`</h4><span><b>`+data[i].type+`</b></span></li>`;
							}
							resp += '</ul>';
							$(".toDiv").css("width", $("#from").width()+' px');
							$(".toDiv").html(resp).show();  
						} else {
							$(".toDiv").html("").hide();
						}
					});
				}, 200);
			});
        });

		function getFromData(e){
			var data = e.split('|');
			$('#from_id').val(data[0]);
			$('#from').val(data[1]);
			$(".fromDiv").hide();
		}
        
		function getToData(e){
			var data = e.split('|');
			$('#to_id').val(data[0]);
			$('#to').val(data[1]);
			$(".toDiv").hide();
		}

    </script>
	<div id="myModal" class="modal fade" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Seyahat Bilgileriniz</h4>
			</div>
			<div class="modal-body">
				<table class='table'></table>
				<hr>
				<p class='fiyat'></p>
				<hr>
				<!--Section: Contact v.2-->
<section class="mb-4">

<!--Section heading-->
<h4 class="h1-responsive font-weight-bold text-center my-4">Iletisim Formu</h4>
<!--Section description-->
<p class="text-center w-responsive mx-auto mb-5">Lutfen formu eksiksiz doldurunuz. En kisa surede size geri donus saglanacaktir.</p>

<div class="row">

	<!--Grid column-->
	<div class="col-md-12 mb-md-0 mb-5">
		<form id="contact-form" name="contact-form" action="mail.php" method="POST">
			<input type='hidden' name='flight_bilgi' id="flight_bilgi">
			<input type='hidden' name='index' id="index">
			<div class="row aralik">
				<div class="col-md-12">
					<div class="md-form mb-0">
						<label for="name" class="">Adiniz Soyadiniz</label>
						<input type="text" id="name" name="name" class="form-control">
					</div>
				</div>
			</div>

			<div class='row aralik'>
				<div class="col-md-12">
					<div class="md-form mb-0">
						<label for="email" class="">E-Posta adresiniz</label>
						<input type="text" id="email" name="email" class="form-control">
					</div>
				</div>
			</div>

			<div class="row aralik">
				<div class="col-md-12">
					<div class="md-form mb-0">
						<label for="subject" class="">Telefon numaraniz</label>
						<input type="text" id="phone" name="phone" class="form-control">
					</div>
				</div>
			</div>

			<div class="row aralik">
				<div class="col-md-12">
					<div class="md-form mb-0">
						<label for="subject" class="">Var ise mesajiniz</label>
						<textarea type="text" id="message" name="message" rows="2" class="form-control md-textarea"></textarea>
					</div>
				</div>
			</div>

		</form>

		<div class="text-center text-md-left aralik">
			<a class="btn btn-primary" onclick="sendBookOrder()">Gonder</a>
		</div>
		<div class="status"></div>
	</div>
	<!--Grid column-->

	

</div>

</section>
<!--Section: Contact v.2-->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
			</div>
			</div>

		</div>
	</div>
</body>

</html>
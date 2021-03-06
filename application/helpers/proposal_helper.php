<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

if (! function_exists ( 'dashLine' )) {
	function dashLine($pdf, $STARTX, $STARTY, $ENDX, $ENDY, $DASHWIDTH, $SPACING) {
		$pdf->SetLineWidth ( 0.1 );
		$SKIPWIDTH = $DASHWIDTH + $SPACING;
		FOR($SEED = 1; $SEED < $ENDX; $SEED = $SEED + $SKIPWIDTH) {
			$pdf->Line ( $STARTX + $SEED, $STARTY, ($STARTX + $DASHWIDTH) + $SEED, $ENDY );
		}
	}
}

if (! function_exists ( 'returnValidation' )) {
	function returnValidation() {
		// $BASESTRING="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$BASESTRING = "0123456789";
		$FINALSTRING = '';
		FOR($SEED = 0; $SEED < 30; $SEED ++) {
			$FINALSTRING .= $BASESTRING [RAND ( 0, 9 )];
		}
		RETURN $FINALSTRING;
	}
}

if (! function_exists ( 'generatePDF' )) {
	function generatePDF($pdf, $CHI_FONT, $ENG_FONT, $DATA, $NAME, $IDNo, $SEX, $BIRTHDAY, $OCCUPATION, $REGADD, $QRImgPath, $SNo, $PHONE) {		
		if($DATA ['district_legislator'] == "廖正井"){
			CPRFormat($pdf, $CHI_FONT, $ENG_FONT, $DATA, $NAME, $IDNo, $SEX, $BIRTHDAY, $OCCUPATION, $REGADD, $QRImgPath, $SNo, $PHONE);
		}
		else{
			appyFormat($pdf, $CHI_FONT, $ENG_FONT, $DATA, $NAME, $IDNo, $SEX, $BIRTHDAY, $OCCUPATION, $REGADD, $QRImgPath, $SNo, $PHONE);
		}
	}
}

if (! function_exists ( 'appyFormat' )) {
	function appyFormat($pdf, $CHI_FONT, $ENG_FONT, $DATA, $NAME, $IDNo, $SEX, $BIRTHDAY, $OCCUPATION, $REGADD, $QRImgPath, $SNo, $PHONE) {
		$pdf->AddPage ();
		$pdf->SetFont ( $CHI_FONT, '', 14 );
		$pdf->SetFillColor ( 255, 255, 255 );
		$pdf->SetTextColor ( 0, 0, 0 );
		$pdf->Cell ( 0, 1, '', 0, 1 );
				
		$add_offset = 104;
		$form_offset = 213;
		$first_line = 104;
		$second_line = 203;
		
		// 說明資訊列===================================
		IF (isset ( $DATA ['prodescimgpath'] )) {
			$pdf->Image ( 'static/image/' . $DATA ['prodescimgpath'], 0, 0, 210 );
		} else {

			$pdf->SetXY ( 10, 11 );
			$pdf->SetFillColor ( 200, 200, 200 );
			$pdf->Cell ( 150, 50, $DATA ['reason'], 1, 1, 'C', true );
			$pdf->SetXY ( 10, 63 );
			$pdf->SetFillColor ( 150, 150, 150 );
			$pdf->Cell ( 150, 30, $DATA ['notice'], 1, 1, 'C', true );
			$pdf->SetXY ( 163, 10 );
			$pdf->SetFillColor ( 100, 100, 100 );
			$pdf->Cell ( 40, 83, $DATA ['others'], 1, 1, 'C', true );
		}
		
		// 地址資訊列===================================
		$pdf->SetXY ( 175, 5 + $add_offset );
		$pdf->Cell ( 20, 25, '5元郵票', 1, 1, 'C', false );
		
		// if (isset ( $DATA ['prepaid'] ) && $DATA ['prepaid'] == 1)
		// {
		// $pdf->Image ( "static/image/adv_mail.jpg", 0, $add_offset, 210 );
		// $pdf->SetXY ( 141.1, 14.7 + $add_offset );
		// $pdf->SetFont ( $CHI_FONT, '', 11 );
		// $pdf->Cell ( 41.5, 7.4, $DATA ['postoffice'] . "郵局登記證", 0, 0, 'C', false );
		// $pdf->SetXY ( 141.1, 22.1 + $add_offset );
		// $pdf->Cell ( 41.5, 7.4, $DATA ['adv_no'], 0, 0, 'C', false );
		// }
		
		$pdf->SetFont ( $CHI_FONT, '', 20 );
		$pdf->SetXY ( 60, 55 + $add_offset );
		$pdf->Cell ( 0, 8, $DATA ['zipcode'], 0, 1, 'L', false );
		$pdf->SetXY ( 60, 63 + $add_offset );
		$pdf->Cell ( 0, 8, $DATA ['mailing_address'], 0, 0, 'L', false );
		$pdf->SetXY ( 60, 76 + $add_offset );
		$pdf->Cell ( 0, 8, $DATA ['receiver'] . '　啟', 0, 0, 'L', false );
		
		if ($QRImgPath != "") {
			// QR Code 影像
			$pdf->Image ( $QRImgPath, 10, 1 + $add_offset, 33 );
			// 刪除 QR Code 影像
			unlink ( $QRImgPath );
			$pdf->SetXY ( 10, 28 + $add_offset );
			$pdf->SetFont ( $CHI_FONT, '', 14 );
			$pdf->Cell ( 33, 12, $SNo, 0, 1, 'C', false );
		}
		// 提議書表單列===================================
		if ($PHONE != "") {
			$pdf->SetXY ( 145, $form_offset - 11 );
			$pdf->SetFont ( $CHI_FONT, '', 14 );
			$pdf->Cell ( 190, 8, '聯絡電話：' . $PHONE, 0, 0, 'L', false );
		}
		
		$pdf->SetXY ( 5, 5 + $form_offset );
		$pdf->SetFont ( $CHI_FONT, '', 24 );
		$pdf->Cell ( 205, 8, '公職人員罷免提議人名冊', 0, 0, 'C', false );
		$pdf->SetFont ( $CHI_FONT, '', 18 );
		
		// $pdf->Cell(0,2,'',0,1);
		
		$pdf->SetXY ( 5, 15 + $form_offset );
		$pdf->SetFont ( $CHI_FONT, '', 12 );
		$pdf->SetFillColor ( 255, 255, 255 );
		$pdf->SetTextColor ( 0, 0, 0 );
		// TODO
		$pdf->Cell ( 200, 8, $DATA ['district_name'] . '立法委員' . $DATA ['district_legislator'] . '罷免案提議人名冊', 1, 1, 'C', false );
		
		$pdf->SetXY ( 5, 23 + $form_offset );
		$pdf->SetFont ( $CHI_FONT, '', 14 );
		$pdf->Cell ( 12, 16, '編號', 1, 0, 'C', true );
		$pdf->Cell ( 40, 8, '姓名', 1, 0, 'C', false );
		$pdf->Cell ( 8, 16, '', 1, 0, 'C', true );
		$pdf->Cell ( 26, 16, '', 1, 0, 'C', true );
		$pdf->Cell ( 20, 16, '職業', 1, 0, 'C', true );
		$pdf->Cell ( 62, 16, '戶籍地址', 1, 0, 'C', true );
		$pdf->Cell ( 20, 16, '', 1, 0, 'C', true );
		$pdf->Cell ( 12, 16, '備註', 1, 0, 'C', true );
		$pdf->Cell ( 20, 8, '', 0, 1 );
		// $pdf->Cell(1);
		$pdf->SetFont ( $CHI_FONT, '', 14 );
		$pdf->SetTextColor ( 0, 0, 0 );
		$pdf->SetXY ( 17, 31 + $form_offset );
		$pdf->Cell ( 40, 8, '身分證字號', 1, 0, 'C', true );
		$pdf->Cell ( 20, 8, '', 0, 1 );
		
		$pdf->SetXY ( 51, 24 + $form_offset );
		$pdf->Cell ( 20, 8, '性', 0, 0, 'C', false );
		$pdf->SetXY ( 51, 30 + $form_offset );
		$pdf->Cell ( 20, 8, '別', 0, 0, 'C', false );
		
		$pdf->SetXY ( 173, 24 + $form_offset );
		$pdf->Cell ( 20, 8, '簽　名', 0, 0, 'C', false );
		$pdf->SetXY ( 173, 30 + $form_offset );
		$pdf->Cell ( 20, 8, '或蓋章', 0, 0, 'C', false );
		
		$pdf->SetXY ( 66, 24 + $form_offset );
		$pdf->Cell ( 24, 8, '出　生', 0, 0, 'C', false );
		$pdf->SetXY ( 66, 30 + $form_offset );
		$pdf->Cell ( 24, 8, '年月日', 0, 0, 'C', false );
		
		$pdf->Cell ( 20, 8, '', 0, 1 );
		
		$pdf->SetXY ( 5, 39 + $form_offset );
		
		$pdf->Cell ( 12, 20, '', 1, 0, 'C', true );
		$pdf->Cell ( 40, 8, $NAME, 1, 0, 'C', false );
		IF ($SEX == "M" || $SEX == "男")
			$SEX_STRING = "男";
		elseif ($SEX == "F" || $SEX == "女")
			$SEX_STRING = "女";
		else
			$SEX_STRING = "";
		
		$pdf->Cell ( 8, 20, $SEX_STRING, 1, 0, 'C', true );
		$pdf->Cell ( 26, 20, $BIRTHDAY, 1, 0, 'C', true );
		$pdf->Cell ( 20, 20, $OCCUPATION, 1, 0, 'C', true );
		$pdf->Cell ( 62, 20, '', 1, 0, 'C', false );
		$pdf->Cell ( 20, 20, '', 1, 0, 'C', true );
		$pdf->Cell ( 12, 20, '', 1, 0, 'C', true );
		$pdf->Cell ( 20, 8, '', 0, 1 );
		// $pdf->Cell(1);
		$pdf->SetFont ( $CHI_FONT, '', 14 );
		$pdf->SetTextColor ( 0, 0, 0 );
		$pdf->SetXY ( 17, 47 + $form_offset );
		$pdf->Cell ( 4, 12, $IDNo [0], 1, 0, 'C', true );
		$pdf->Cell ( 4, 12, $IDNo [1], 1, 0, 'C', true );
		$pdf->Cell ( 4, 12, $IDNo [2], 1, 0, 'C', true );
		$pdf->Cell ( 4, 12, $IDNo [3], 1, 0, 'C', true );
		$pdf->Cell ( 4, 12, $IDNo [4], 1, 0, 'C', true );
		$pdf->Cell ( 4, 12, $IDNo [5], 1, 0, 'C', true );
		$pdf->Cell ( 4, 12, $IDNo [6], 1, 0, 'C', true );
		$pdf->Cell ( 4, 12, $IDNo [7], 1, 0, 'C', true );
		$pdf->Cell ( 4, 12, $IDNo [8], 1, 0, 'C', true );
		$pdf->Cell ( 4, 12, $IDNo [9], 1, 0, 'C', true );
		$pdf->Cell ( 20, 8, '', 0, 1 );
		
		dashLine ( $pdf, 5, $first_line, 200, $first_line, 2, 2 );
		dashLine ( $pdf, 5, $second_line, 200, $second_line, 2, 2 );
		$pdf->SetFont ( $CHI_FONT, '', 12 );
		$ADDLEN = MB_STRLEN ( $REGADD, "UTF-8" );
		$WORDPERLINE = 14;
		$LINE = ($ADDLEN - $ADDLEN % $WORDPERLINE) / $WORDPERLINE;
		IF (($ADDLEN % $WORDPERLINE) > 0) {
			$LINE ++;
		}
		IF ($LINE == "" || $LINE == 0)
			$LINE = 1;
		$HEIGHT = 20 / $LINE;
		FOR($LINESEED = 0; $LINESEED < $LINE; $LINESEED ++) {
			$pdf->SetXY ( 111, 39 + $form_offset + $LINESEED * $HEIGHT );
			$pdf->Cell ( 62, $HEIGHT, MB_SUBSTR ( $REGADD, $LINESEED * $WORDPERLINE, $WORDPERLINE, "UTF-8" ), 'C', false );
		}
		
		$pdf->SetXY ( 95, 2 );
		$pdf->SetFont ( $CHI_FONT, '', 10 );
		$pdf->Cell ( 24, 7, "請以膠帶黏貼", 1, 0, 'C', true );
	}
}

if (! function_exists ( 'CPRFormat' )) {
	function CPRFormat($pdf, $CHI_FONT, $ENG_FONT, $DATA, $NAME, $IDNo, $SEX, $BIRTHDAY, $OCCUPATION, $REGADD, $QRImgPath, $SNo, $PHONE) {
		
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->setMargins(20, 10, -1);
		$pdf->AddPage ('L');
		
		$x = 10;
		$y = 10;
		$w = 277;
		//$pdf->Image('static/image/CPRimage/P1.jpg', $x, $y, $w, $h, 'JPG', '', 'T', true, 300, 'C', false, false, '', 'CM', false);
		$pdf->Image('static/image/CPRimage/P1.jpg', $x, $y, $w);
		
		if ($QRImgPath != "") {
			// QR Code 影像
			$x = 200;
			$y = 37;
			$pdf->Image ( $QRImgPath, $x, $y, 39 );
	
			// 刪除 QR Code 影像
			unlink ( $QRImgPath );
			$pdf->SetXY ( $x + 3, $y + 32 );
			$pdf->SetFont ( $CHI_FONT, '', 14 );
			$pdf->Cell ( 33, 12, $SNo, 0, 1, 'C', false );
		}
	
		// 提議書表單列===================================
		$pdf->SetFont ( $CHI_FONT, 'U', 16 );
		$pdf->SetFillColor ( 255, 255, 255 );
		$pdf->SetTextColor ( 0, 0, 0 );
		$pdf->SetXY ( 115, 95);
		$pdf->Cell ( 67, 0, '公職人員罷免提議人名冊', '', 0, 'C', false );
		
		$form_offset = 100;
			
		$pdf->SetXY ( 10, 15 + $form_offset );
		$pdf->SetFont ( $CHI_FONT, '', 12 );
		$pdf->SetFillColor ( 255, 255, 255 );
		$pdf->SetTextColor ( 0, 0, 0 );
		// TODO
		$pdf->Cell ( 277, 12, $DATA ['district_name'] . '立法委員' . $DATA ['district_legislator'] . '罷免案提議人名冊', 1, 1, 'C', false );
		
		$pdf->SetXY ( 10, 27 + $form_offset );
		$pdf->SetFont ( $CHI_FONT, '', 14 );
		$pdf->Cell ( 17, 24, '編號', 1, 0, 'C', true );
		$pdf->Cell ( 60, 12, '姓名', 1, 0, 'C', false );
		$pdf->Cell ( 10, 24, '', 1, 0, 'C', true );
		$pdf->Cell ( 36, 24, '', 1, 0, 'C', true );
		$pdf->Cell ( 28, 24, '職業', 1, 0, 'C', true );
		$pdf->Cell ( 81, 24, '戶籍地址', 1, 0, 'C', true );
		$pdf->Cell ( 28, 24, '', 1, 0, 'C', true );
		$pdf->Cell ( 17, 24, '備註', 1, 0, 'C', true );
		$pdf->Cell ( 28, 12, '', 0, 1 );
		// $pdf->Cell(1);
		$pdf->SetFont ( $CHI_FONT, '', 14 );
		$pdf->SetTextColor ( 0, 0, 0 );
		$pdf->SetXY ( 27, 39 + $form_offset );
		$pdf->Cell ( 60, 12, '身分證字號', 1, 0, 'C', true );
		$pdf->Cell ( 28, 12, '', 0, 1 );
		
		$pdf->SetXY ( 87, 27 + $form_offset );
		$pdf->Cell ( 10, 12, '性', 0, 0, 'C', false );
		$pdf->SetXY ( 87, 39 + $form_offset );
		$pdf->Cell ( 10, 12, '別', 0, 0, 'C', false );
		
		$pdf->SetXY ( 242, 27 + $form_offset );
		$pdf->Cell ( 28, 12, '簽　名', 0, 0, 'C', false );
		$pdf->SetXY ( 242, 39 + $form_offset );
		$pdf->Cell ( 28, 12, '或蓋章', 0, 0, 'C', false );
		
		$pdf->SetXY ( 97, 27 + $form_offset );
		$pdf->Cell ( 36, 12, '出　生', 0, 0, 'C', false );
		$pdf->SetXY ( 97, 39 + $form_offset );
		$pdf->Cell ( 36, 12, '年月日', 0, 0, 'C', false );
		
		$pdf->SetXY ( 10, 51 + $form_offset );
		
		$pdf->Cell ( 17, 30, '', 1, 0, 'C', true );
		$pdf->Cell ( 60, 15, $NAME, 1, 0, 'C', false );
		IF ($SEX == "M" || $SEX == "男")
		$SEX_STRING = "男";
		elseif ($SEX == "F" || $SEX == "女")
		$SEX_STRING = "女";
		else
			$SEX_STRING = "";
		
		$pdf->Cell ( 10, 30, $SEX_STRING, 1, 0, 'C', true );
		$pdf->Cell ( 36, 30, $BIRTHDAY, 1, 0, 'C', true );
		$pdf->Cell ( 28, 30, $OCCUPATION, 1, 0, 'C', true );
		$pdf->Cell ( 81, 30, '', 1, 0, 'C', false );
		$pdf->Cell ( 28, 30, '', 1, 0, 'C', true );
		$pdf->Cell ( 17, 30, '', 1, 0, 'C', true );
		$pdf->Cell ( 28, 12, '', 0, 1 );
		// $pdf->Cell(1);
		$pdf->SetFont ( $CHI_FONT, '', 14 );
		$pdf->SetTextColor ( 0, 0, 0 );
		$pdf->SetXY ( 27, 66 + $form_offset );
		$pdf->Cell ( 6, 15, $IDNo [0], 1, 0, 'C', true );
		$pdf->Cell ( 6, 15, $IDNo [1], 1, 0, 'C', true );
		$pdf->Cell ( 6, 15, $IDNo [2], 1, 0, 'C', true );
		$pdf->Cell ( 6, 15, $IDNo [3], 1, 0, 'C', true );
		$pdf->Cell ( 6, 15, $IDNo [4], 1, 0, 'C', true );
		$pdf->Cell ( 6, 15, $IDNo [5], 1, 0, 'C', true );
		$pdf->Cell ( 6, 15, $IDNo [6], 1, 0, 'C', true );
		$pdf->Cell ( 6, 15, $IDNo [7], 1, 0, 'C', true );
		$pdf->Cell ( 6, 15, $IDNo [8], 1, 0, 'C', true );
		$pdf->Cell ( 6, 15, $IDNo [9], 1, 0, 'C', true );
		//$pdf->Cell ( 20, 12, '', 0, 1 );
		
		$pdf->SetFont ( $CHI_FONT, '', 14 );
		$ADDLEN = MB_STRLEN ( $REGADD, "UTF-8" );
		$WORDPERLINE = 14;
		$LINE = ($ADDLEN - $ADDLEN % $WORDPERLINE) / $WORDPERLINE;
		IF (($ADDLEN % $WORDPERLINE) > 0) {
			$LINE ++;
		}
		IF ($LINE == "" || $LINE == 0)
		$LINE = 1;
		$HEIGHT = 30 / $LINE;
		FOR($LINESEED = 0; $LINESEED < $LINE; $LINESEED ++) {
			$pdf->SetXY ( 161, 51 + $form_offset + $LINESEED * $HEIGHT );
			$pdf->Cell ( 81, $HEIGHT, MB_SUBSTR ( $REGADD, $LINESEED * $WORDPERLINE, $WORDPERLINE, "UTF-8" ), 'C', false );
		}
		
		$pdf->setMargins(0, 10, -1);
		$pdf->AddPage ('PORTRAIT');		
		//$pdf->Image ( 'static/image/CPRimage/P2.jpg', 0, 0, 595, 842);
		$x = 0;
		$y = 93;
		$w = 210;
		$h = 100;
		$pdf->Image('static/image/CPRimage/P2.jpg', $x, $y, $w, $h, 'JPG', '', 'M', false, 200, 'C', false, false, '', 'CM', false, true);
		
	}
}
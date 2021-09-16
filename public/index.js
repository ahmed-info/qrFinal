 var qrData = document.getElementById("qr-data");
      var qrcode = new QRCode(document.getElementById("qrcode"), {
        width: 360,
        height: 360,
        correctLevel: QRCode.CorrectLevel.H,
      });
      function generateQR() {
        var data = qrData.value;
        qrcode.makeCode(data);
      }
export const singleNdcJs = function () {

    // e-sign a file
    function myFunction() {
        var x = document.getElementById("tsaurls").value;
        if (x != 0) {
            document.getElementById("tsaURL").value = x;
        } else {
            document.getElementById("tsaURL").value = "";
        }
    }
    $(document).ready(function () {
        $('#w1').hide();

        $('#btnDecryptVerify').hide();
        $('#btnDecryptVerifyWithCrt').hide();

        var initConfig = {
            "preSignCallback": function () {
                // do something 
                // based on the return sign will be invoked

                $('#ndcAthorityIssueModal').modal('hide');
                return true;
            },
            "postSignCallback": function (alias, sign, key) {
                $('#signedPdfData').val(sign);
                $('#lblEncryptedKey').val(key);

                //									$('#btnDecryptVerify').show();
                $('#btnDecryptVerifyWithCrt').show();

            },
            signType: 'pdf',
            mode: 'batch',
            certificateData: $('#cert').val()

        };
        dscSigner.configure(initConfig);

        $('#cert').bind('input propertychange', function () {
            var initConfig = {
                "preSignCallback": function () {
                    // do something before signing
                    alert("Pre-sign event fired");
                    return true;
                },
                "postSignCallback": function (alias, sign, key) {
                    //do something after signing

                    $('#signedPdfData').val(sign);
                    $('#lblEncryptedKey').val(key);

                    //$('#btnDecryptVerify').show();
                    $('#btnDecryptVerifyWithCrt').show();

                },
                signType: 'pdf',
                mode: 'batch',
                certificateData: $('#cert').val()
            };
            dscSigner.configure(initConfig);
        });

        $('#signPdf').click(function () {
            batchInitAuto();
            var data = $("#pdfData").val();

            if (data != null || data != '') {
                token = $("#txtBatchToken").val()
                dscSigner.signpdfbatch(data, token);
                $("#txtBatchSize").val(dscSigner.batchsize());

                if (dscSigner.batchsize() == 0) {
                    $("#txtBatchSize").removeAttr("disabled");
                    $("#btnInitBatch").removeAttr("disabled");
                }
            }
            $('#signPdf').hide();
        });

        function batchInitAuto() {
            var batch_size = $("#txtBatchSize").val();
            $("#txtBatchToken").val(dscSigner.initbatch(batch_size));
            $("#txtBatchSize").attr("disabled", "disabled");
            $("#btnInitBatch").attr("disabled", "disabled");
            var c = $("#txtBatchToken").val();
        }

        $('#btnInitBatch').click(function () {
            var batch_size = $("#txtBatchSize").val();
            $("#txtBatchToken").val(dscSigner.initbatch(batch_size));
            $("#txtBatchSize").attr("disabled", "disabled");
            $("#btnInitBatch").attr("disabled", "disabled");
        });

        $('#btnDecryptVerify').click(function () {

            var sign = $('#signedPdfData').val();
            var key = $('#lblEncryptedKey').val();

            // Implement Decrypt Verify here
            var requestData = {
                action: "DECRYPT_VERIFY",
                en_sig: sign,
                ek: key
            };

            $.ajax({
                url: dscapibaseurl + "/pdfsignature",
                type: "post",
                dataType: "json",
                contentType: 'application/json',
                data: JSON.stringify(requestData),
                async: false
            }).done(function (data) {
                if (data.status_cd == 1) {
                    var jsonData = JSON.parse(atob(data.data));
                    $('#decryptedSignature').val(jsonData.sig);
                    $('#decodedSignedXML').val(atob(jsonData.sig));
                    $('#verifiedSignature').val(atob(data.data));
                    $('#verificationResponse').val(atob(data.data));

                    //Set Class to download link
                    $('#downloadDiv').addClass('btn btn-info');
                    //get pdf data
                    var pdfData = jsonData.sig;
                    var dlnk = document.getElementById('downloadDiv');
                    dlnk.href = 'data:application/pdf;base64,' + pdfData;
                    $("#downloadDiv").text("Download Signed PDF File");

                    $('#btnDecryptVerify').hide();
                    $('#btnDecryptVerifyWithCrt').hide();
                } else {
                    alert("Verification Failed");
                }

            }).fail(function (jqXHR, textStatus, errorThrown) {
                alert(textStatus);
            });
        });
        var getIssuesPdfBase64 = "";

        $('#btnDecryptVerifyWithCrt').click(function () {

            $('#verificationResponse').val('');

            var sign = $('#signedPdfData').val();
            var key = $('#lblEncryptedKey').val();

            // Implement Verify here
            var requestData = {
                action: "DECRYPT_VERIFY_WITH_CERT",
                en_sig: sign,
                ek: key,
                certificate: $('#cert').val()
            };
            $.ajax({
                url: dscapibaseurl + "/pdfsignature",
                type: "post",
                dataType: "json",
                contentType: 'application/json',
                data: JSON.stringify(requestData),
                async: false
            }).done(function (data) {
                if (data.status_cd == 1) {
                    var jsonData = JSON.parse(atob(data.data));
                    $('#decryptedSignature').val(jsonData.sig);
                    $('#decodedSignedXML').val(atob(jsonData.sig));
                    $('#verifiedSignature').val(atob(data.data));
                    $('#verificationResponse').val(atob(data.data));

                    //Set Class to download link
                    $('#downloadDiv').addClass('btn btn-info');
                    //get pdf data
                    var pdfData = jsonData.sig;
                    getIssuesPdfBase64 = pdfData;
                    $('#signedPdfData').val(getIssuesPdfBase64);
                    $('#saveBtnBase64issuedpdf').show();
                    $('#btnDecryptVerifyWithCrt').hide();

                    // var dlnk = document.getElementById('downloadDiv');
                    // dlnk.href = 'data:application/pdf;base64,' + pdfData;
                    // $("#downloadDiv").text("Download Signed PDF File");

                    // $('#btnDecryptVerify').hide();
                    //$('#btnDecryptVerifyWithCrt').hide();
                } else {
                    $('#verificationResponse').val(JSON.stringify(data));
                    alert("Verification Failed");
                }

            }).fail(function (jqXHR, textStatus, errorThrown) {
                alert(textStatus);
            });
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    var data = e.target.result;
                    var base64 = data.replace(/^[^,]*,/, '');
                    $("#pdfData").val(base64);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#pdfFile").change(function () {
            readURL(this);
        });

    });
    // end e-sign a file
    // esign................................
    $(document).ready(function () {

        $('#loadCert').click(function () {
            var serialNo = $('#serialNum').val();
            var cert = $('#cert').val();
            if (serialNo == "" && cert == "") {
                $.blockUI({
                    message: '<h5><img src="resources/images/please-wait-fb.gif" /> Initializing NICDSign.Please Wait...</h5>'
                });
            }
            setTimeout(
                function () {
                    if (serialNo == "" && cert == "") {
                        $(document).ajaxStop($.unblockUI);
                        getDSCDetails();
                    }
                }, 3000);
        });

        function getDSCDetails() {

            dscSigner.certificate(function (res) {
                $('#cname').val(res.certificates[0].subject);
                $('#pan').val(res.certificates[0].pan);
                $('#serialNum').val(res.certificates[0].serialNumber);
                $('#validFrom').val(res.certificates[0].notBefore);
                $('#validTo').val(res.certificates[0].notAfter);
                $('#cert').val(res.certificates[0].certificate);
                $('#sts').val("ACTIVE");
                $('#panel').hide();
            });
        }

        var serialNo = $('#serialNum').val();
        var cert = $('#cert').val();
        if (serialNo == "" && cert == "") {
            $.blockUI({
                message: '<h5><img src="resources/images/please-wait-fb.gif" /> Initializing NICDSign.Please Wait...</h5>'
            });
        }
        setTimeout(
            function () {
                if (serialNo == "" && cert == "") {
                    $(document).ajaxStop($.unblockUI);
                    getDSCDetails();
                }
            }, 3000);
    });
    // end .............................................................
}
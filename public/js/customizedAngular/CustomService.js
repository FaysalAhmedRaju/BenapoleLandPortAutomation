angular.module('customServiceModule', [])
    .factory('manifestService', function () {
        return {
			addYearWithManifest: function (manifest, keyboardStatus, key) {
				key = (key || 'manifestno').toLowerCase();
				if (keyboardStatus == true && key == 'manifestno') {
					var today = new Date();
					var pattern = /^([0-9]{1,10}|[0-9(P|p)]{2,6}|[0-9(ch|CH)]{2,6})[\/]{1}([0-9]{1,3}|[(A-Z)]{1}|[(A-Z-A-Z)]{3})[\/]{1}$/;
					if (pattern.test(manifest)) {
						manifest = manifest + today.getFullYear();
					}
				}
				return manifest;
			},
			addYearWithVoucher: function (voucher, keyboardStatus, key) {

				console.log(voucher)
				console.log(keyboardStatus)




				key = (key || 'manifestno').toLowerCase();
				if (keyboardStatus == true) {
					console.log('ok')
					var today =new Date().getFullYear().toString().substr(-2)
                    console.log(new Date().getFullYear().toString().substr(-2));
					var pattern = /^([0-9]{1,10})[\/]{1}$/;
					if (pattern.test(voucher)) {
						voucher = voucher + today;
						console.log('test passed')
					}
				}
				return voucher;
			},



				getKeyboardStatus : function(keyboardEvent) {
					var key = keyboardEvent.keyCode || null;
		            if(key == 8) {
		               return false;
		            } else {
		               return true;
		            }
				}



			};
		})
	.factory('enterKeyService', function () {
	return {
		enterKey: function reverseString(name) {
			$(name).on("keypress", function (e) {
				// ENTER PRESSED
				if (e.keyCode == 13) {
					// FOCUS ELEMENT
					var inputs = $(this).parents("form").eq(0).find(":input");
					var idx = inputs.index(this);
					//console.log(idx)
					//console.log(inputs.length - 1)
					if (idx == inputs.length - 1) {
						//console.log('if')
						inputs[0].select()
					} else {
						//console.log('else')
						inputs[idx + 1].focus(); //  handles submit buttons
						inputs[idx + 1].select();
					}
					return false;
				}
			});
		}
	}
})

	.filter('dangerous', function () {
		return function (val) {
			var type;
			if (val == 1) {
				return type = '200%';
			}
			else {
				return type = '';
			}
		}})


	.factory('amountToTextService', function () {
		return {
			amountToText: function (amount) {

				console.log(amount)
				var words = new Array();
				words[0] = '';
				words[1] = 'One';
				words[2] = 'Two';
				words[3] = 'Three';
				words[4] = 'Four';
				words[5] = 'Five';
				words[6] = 'Six';
				words[7] = 'Seven';
				words[8] = 'Eight';
				words[9] = 'Nine';
				words[10] = 'Ten';
				words[11] = 'Eleven';
				words[12] = 'Twelve';
				words[13] = 'Thirteen';
				words[14] = 'Fourteen';
				words[15] = 'Fifteen';
				words[16] = 'Sixteen';
				words[17] = 'Seventeen';
				words[18] = 'Eighteen';
				words[19] = 'Nineteen';
				words[20] = 'Twenty';
				words[30] = 'Thirty';
				words[40] = 'Forty';
				words[50] = 'Fifty';
				words[60] = 'Sixty';
				words[70] = 'Seventy';
				words[80] = 'Eighty';
				words[90] = 'Ninety';
				amount = amount.toString();
				var atemp = amount.split(".");
				var number = atemp[0].split(",").join("");
				var n_length = number.length;
				var words_string = "";
				if (n_length <= 9) {
					var n_array = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0);
					var received_n_array = new Array();
					for (var i = 0; i < n_length; i++) {
						received_n_array[i] = number.substr(i, 1);
					}
					for (var i = 9 - n_length, j = 0; i < 9; i++, j++) {
						n_array[i] = received_n_array[j];
					}
					for (var i = 0, j = 1; i < 9; i++, j++) {
						if (i == 0 || i == 2 || i == 4 || i == 7) {
							if (n_array[i] == 1) {
								n_array[j] = 10 + parseInt(n_array[j]);
								n_array[i] = 0;
							}
						}
					}
					value = "";
					for (var i = 0; i < 9; i++) {
						if (i == 0 || i == 2 || i == 4 || i == 7) {
							value = n_array[i] * 10;
						} else {
							value = n_array[i];
						}
						if (value != 0) {
							words_string += words[value] + " ";
						}
						if ((i == 1 && value != 0) || (i == 0 && value != 0 && n_array[i + 1] == 0)) {
							words_string += "Crores ";
						}
						if ((i == 3 && value != 0) || (i == 2 && value != 0 && n_array[i + 1] == 0)) {
							words_string += "Lakhs ";
						}
						if ((i == 5 && value != 0) || (i == 4 && value != 0 && n_array[i + 1] == 0)) {
							words_string += "Thousand ";
						}
						if (i == 6 && value != 0 && (n_array[i + 1] != 0 && n_array[i + 2] != 0)) {
							words_string += "Hundred and ";
						} else if (i == 6 && value != 0) {
							words_string += "Hundred ";
						}
					}
					words_string = words_string.split("  ").join(" ");
				}
				return words_string;
			}
		}
})



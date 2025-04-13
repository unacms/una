/*!
 * Select2-to-Tree 1.1.1
 * https://github.com/clivezhg/select2-to-tree
 */
(function ($) {
	$.fn.select2ToTree = function (options) {
		var opts = $.extend({}, options);

		if (opts.treeData) {
			buildSelect(opts.treeData, this);
		}

		opts._templateResult = opts.templateResult;
		opts.templateResult = function (data, container) {
			var label = data.text;
			if (typeof opts._templateResult === "function") {
				label = opts._templateResult(data, container);
			}
			var $iteme = $("<span class='item-label'></span>").append(label);
			if (data.element) {
				var ele = data.element;
				container.setAttribute("data-val", ele.value);
				if (ele.className) container.className += " " + ele.className;
				if (ele.getAttribute("data-pup")) {
					container.setAttribute("data-pup", ele.getAttribute("data-pup"));
				}
				if ($(container).hasClass("non-leaf")) {
					return $.merge($('<span class="expand-collapse" onmouseup="expColMouseupHandler(event);"></span>'), $iteme);
				}
			}
			return $iteme;
		};

		window.expColMouseupHandler = function (evt) {
			toggleSubOptions(evt.target || evt.srcElement);
			/* prevent Select2 from doing "select2:selecting","select2:unselecting","select2:closing" */
			evt.stopPropagation ? evt.stopPropagation() : evt.cancelBubble = true;
			evt.preventDefault ? evt.preventDefault() : evt.returnValue = false;
		}

		var s2inst = this.select2(opts);

		s2inst.on("select2:open", function (evt) {
			var s2data = s2inst.data("select2");
			s2data.$dropdown.addClass("s2-to-tree");
			s2data.$dropdown.removeClass("searching-result");
			var $allsch = s2data.$dropdown.find(".select2-search__field").add( s2data.$container.find(".select2-search__field") );
			$allsch.off("input", inputHandler);
			$allsch.on("input", inputHandler);
		});

		/* Show search result options even if they are collapsed */
		function inputHandler(evt) {
			var s2data = s2inst.data("select2");
			if ($(this).val().trim().length > 0) {
				s2data.$dropdown.addClass("searching-result");
			}
			else {
				s2data.$dropdown.removeClass("searching-result");
			}
		}

		return s2inst;
	};

 	/* Build the Select Option elements */
	function buildSelect(treeData, $el) {

		/* Support the object path (eg: `item.label`) for 'valFld' & 'labelFld' */
		function readPath(object, path) {
			var currentPosition = object;
			for (var j = 0; j < path.length; j++) {
				var currentPath = path[j];
				if (currentPosition[currentPath]) {
					currentPosition = currentPosition[currentPath];
					continue;
				}
				return 'MISSING';
			}
			return currentPosition;
		}

		function buildOptions(dataArr, curLevel, pup) {
			var labelPath;
			if (treeData.labelFld && treeData.labelFld.split('.').length> 1){
				labelPath = treeData.labelFld.split('.');
			}
			var idPath;
			if (treeData.valFld && treeData.valFld.split('.').length > 1) {
				idPath = treeData.valFld.split('.');
			}

			for (var i = 0; i < dataArr.length; i++) {
				var data = dataArr[i] || {};
				var $opt = $("<option></option>");
				if (labelPath) {
					$opt.text(readPath(data, labelPath));
				} else {
					$opt.text(data[treeData.labelFld || "text"]);
				}
				if (idPath) {
					$opt.val(readPath(data, idPath));
				} else {
					$opt.val(data[treeData.valFld || "id"]);
				}
				if (data[treeData.selFld || "selected"] && String(data[treeData.selFld || "selected"]) === "true") {
					$opt.prop("selected", data[treeData.selFld || "selected"]);
				}
				if($opt.val() === "") {
					$opt.prop("disabled", true);
					$opt.val(getUniqueValue());
				}
				$opt.addClass("l" + curLevel);
				if (pup) $opt.attr("data-pup", pup);
				$el.append($opt);
				var inc = data[treeData.incFld || "inc"];
				if (inc && inc.length > 0) {
					$opt.addClass("non-leaf");
					buildOptions(inc, curLevel+1, $opt.val());
				}
			} // end 'for'
		} // end 'buildOptions'

		buildOptions(treeData.dataArr, 1, "");
		if (treeData.dftVal) $el.val(treeData.dftVal);
	}

	var uniqueIdx = 1;
	function getUniqueValue() {
		return "autoUniqueVal_" + uniqueIdx++;
	}

	function toggleSubOptions(target) {
		$(target.parentNode).toggleClass("opened");
		showHideSub(target.parentNode);
	}

	function showHideSub(ele) {
		var curEle = ele;
		var $options = $(ele).parent(".select2-results__options");
		var shouldShow = true;
		do {
			var pup = ($(curEle).attr("data-pup") || "").replace(/'/g, "\\'");
			curEle = null;
			if (pup) {
				var pupEle = $options.find(".select2-results__option[data-val='" + pup + "']");
				if (pupEle.length > 0) {
					if (!pupEle.eq(0).hasClass("opened")) { // hide current node if any parent node is collapsed
						$(ele).removeClass("showme");
						shouldShow = false;
						break;
					}
					curEle = pupEle[0];
				}
			}
		} while (curEle);
		if (shouldShow) $(ele).addClass("showme");

		var val = ($(ele).attr("data-val") || "").replace(/'/g, "\\'");
		$options.find(".select2-results__option[data-pup='" + val + "']").each(function () {
			showHideSub(this);
		});
	}
})(jQuery);

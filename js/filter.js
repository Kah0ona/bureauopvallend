jQuery(document).ready(function($){
		var data = gameCategories;
		/**
		 *  {
		 *     113 : [1234, 1235, 1236] ,
		 *	   114 : [1234],
		 *	}
		 */
		function buildPostsByCategoryMap(data){
			var postsByCategoryId = {};
			//build postsByCategory map
			for(var i = 0; i < data.length; i++){
				var post = data[i];
				var cats = post.categories;
				for(var key in cats){
					if(postsByCategoryId[key] == null || postsByCategoryId[key] == undefined){
						postsByCategoryId[key] = [];
					}

					postsByCategoryId[key].push(post.id);
				}
			}
			return postsByCategoryId;
		}

		var filterStruct = {};
		var titleByIdMap = {};
		var postsByCategoryId = buildPostsByCategoryMap(data); 

		function arrayContains(arr, nameVal){
			for(var i = 0; i < arr.length; i++){
				if(arr[i].name == nameVal){
					return true;
				}
			}
			return false;
		}


		//loop over the data, and get everything with parent = 0, and create a title
		for(var i = 0; i < data.length; i++){
			var post = data[i];
			var cats = post.categories;
			for(var key in cats){
				var cat = cats[key];
				var name = cat.name;
				if(cat.parent === 0 && !(name in filterStruct)) {// root, and not yet in filterStruct, so add new array
					filterStruct[name] = [];
					titleByIdMap[key] = name;
				}
			}
		}
	
		//now add the values
		for(var i = 0; i < data.length; i++){
			var post = data[i];
			var cats = post.categories;
			var id = post.id;
			for(var key in cats){
				var cat = cats[key];
				var name = cat.name;
				if(cat.parent !== 0) {// not root
					if(!arrayContains(filterStruct[titleByIdMap[cat.parent]], name) ){
						filterStruct[titleByIdMap[cat.parent]].push({ "name" : name, "id" : cat.term_id});
					}
				}
			}
		}

		Array.prototype.unique = function() {
			var a = this.concat();
			for(var i=0; i<a.length; ++i) {
				for(var j=i+1; j<a.length; ++j) {
					if(a[i] === a[j])
						a.splice(j--, 1);
				}
			}

			return a;
		};

		//render it
		var output = "";
		for(var type in filterStruct){
			var items = filterStruct[type];
			if(items.length > 0){
				output += "<h4>"+type+"</h4>";
				for(var i = 0; i < items.length; i++){
					var item = items[i];
					output += "<input type='checkbox' class='gamefiltercheckbox' value='"+item.id+"'/> "+item.name+" ("+postsByCategoryId[item.id].length+")<br/>";
				}
			}
		}

		function doFilter(){
			//check all CHECKED checkboxes, their value. 
			var checkedPosts = [];
			var numChecked = 0;
			$('.gamefiltercheckbox:checked').each(function(){
				numChecked++;
				checkedPosts = checkedPosts.concat(postsByCategoryId[$(this).val()]);
			});

			//special case; show everything
			if(numChecked == 0){
				$('.image-grid').isotope({filter : '*'});
				return;
			}

			checkedPosts = checkedPosts.unique();

			var filterString = "";
			for(var i = 0; i < checkedPosts.length; i++){
				var id = checkedPosts[i];
//				$('.image-grid > li[data-id="'+id+'"]').show();
				filterString += '.isotope-item[data-id="'+id+'"], ';
			}

			filterString += ".dummmmy";
			console.log(filterString);
			$('.image-grid').isotope({ filter: filterString })
		}

		//loop over the children and create a checkbox
		$('.widget_bureauopvallend_filterwidget .widget-inside').append("<div>"+output+"</div>");
		$('.gamefiltercheckbox').on('change', function(event){
			doFilter();
		});

	

});

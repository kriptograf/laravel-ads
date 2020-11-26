/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

//window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/*const app = new Vue({
    el: '#app',
});*/

$('.region-selector').each(function () {
	var block = $(this);
	var selected = block.data('selected');
	var url = block.data('source');
	
	var buildSelect = function (parent, items) {
		var current = items[0];
		var select = $('<select class="form-control">');
		var group = $('<div class="form-group">');

		select.append($('<option value=""></option>'));
		group.append(select);
		block.append(group);

		axios.get(url, {params: {parent: parent}})
			.then(function (response) {
				response.data.forEach(function (region) {
					select.append(
						$('<option>')
							.attr('name', 'regions[]')
							.attr('value', region.id)
							.attr('selected', region.id === current)
							.text(region.name)
					);
				});
				if (current) {
					buildSelect(current, items.slice(1));
				}
			})
			.catch(function (error) {
				console.log(error);
			});
	};

	buildSelect(null, selected);
});


$.get('/ajax/regions', function (result) {
	console.log(result);
});

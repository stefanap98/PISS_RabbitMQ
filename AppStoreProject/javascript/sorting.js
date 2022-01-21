const ascending = 1;
const descending = -1;
var ord = ascending;

function cmp_id(tag1,tag2){
	var quallity_t1 = tag1.getAttribute('id');
	var quallity_t2 = tag2.getAttribute('id');
	if (parseInt(quallity_t1) < parseInt(quallity_t2)) return ord *-1;
	if (parseInt(quallity_t1) > parseInt(quallity_t2)) return ord * 1;
	return 0;
}

function cmp_date(tag1,tag2){
	var quallity_t1 =tag1.getAttribute('uploaded_time');
	var quallity_t2 =tag2.getAttribute('uploaded_time');
	if (quallity_t1 < quallity_t2) return ord * 1;
	if (quallity_t1 > quallity_t2) return ord * -1;
	return 0;
}
function cmp_name(tag1,tag2){
	var quallity_t1 = tag1.getAttribute('project_name');
	var quallity_t2 = tag2.getAttribute('project_name');
	if (quallity_t1 < quallity_t2) return ord * -1;
	if (quallity_t1 > quallity_t2) return ord * 1;
	return 0;
}
function cmp_group(tag1,tag2){
	var quallity_t1 = tag1.getAttribute('project_group');
	var quallity_t2 = tag2.getAttribute('project_group');
	if (parseInt(quallity_t1) < parseInt(quallity_t2)) return ord *-1;
	if (parseInt(quallity_t1) > parseInt(quallity_t2)) return ord * 1;
	return 0;
}

function Sort(){
	var criteria = document.getElementById('sort_criteria').value;
	var order = document.getElementById('sort_type').value;

	var all_nodes = document.getElementById("projects").childNodes;
	var flt = [];

	for (node in all_nodes){
		if(all_nodes[node].tagName == 'DIV') flt.push(all_nodes[node]);
	}
	switch(order){
		case 'asc':
			ord =ascending;
			break;
		case 'desc':
			ord = descending;
			break;
	}
	switch(criteria){
		case'date':
			flt = flt.sort(cmp_id);
			allProjects = allProjects.sort(cmp_id);
			break;
		case 'last_uploaded':
			flt = flt.sort(cmp_date);
			allProjects = allProjects.sort(cmp_date);
			break;
		case 'name':
			flt = flt.sort(cmp_name);
			allProjects = allProjects.sort(cmp_name);
			break;
		case 'group':
			flt = flt.sort(cmp_group);
			allProjects = allProjects.sort(cmp_group);
			break;
	}
	document.getElementById("projects").innerHTML = '';
	var prj = document.getElementById("projects");
	for (node in flt){
		prj.appendChild(flt[node]);
	}

}
const prjList = document.getElementById('projects');
const searchBar = document.getElementById('searchBar');
let allProjects = [];
function in_props(tag, item){
	var attr = tag.getAttribute("project_name").toLowerCase();
	return  attr.includes(item)||item == "";
}

searchBar.addEventListener('keyup', (e) => {
    const searchString = e.target.value.toLowerCase();
    const filtered = allProjects.filter((project) => {
        return in_props(project,searchString);
    });
    prjList.innerHTML = "";
    display(filtered);
});

const display = (flt) => {
	for (node in flt){
		prjList.appendChild(flt[node]);
	}
};

function load_sug(){
	console.log("loaded");
	var all_nodes = document.getElementById("projects").childNodes;
	for (node in all_nodes){
		if(all_nodes[node].tagName == 'DIV') allProjects.push(all_nodes[node]);
		console.log(all_nodes[node]);
	}

}

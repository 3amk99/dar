let nom = document.getElementById("name_1");
let couleur = document.getElementById("couleur");
let icone = document.getElementById("icone");
let Form = document.getElementById("Form");

function showdata()
{
    fetch('backend/api.php')
    .then(res=>res.json())
    .then(data1=>{
      let container = document.getElementById("tableBody");
      container.innerHTML = "";
      data1.data.forEach(element =>{
       let container_mini = document.createElement("tr");
       container_mini.innerHTML = `
       <td class="border-2 border-black">${element.name}</td>
       <td>${element.color}</td>
       <td>${element.icon}</td>
       `;
       container.appendChild(container_mini);
      });
      console.log(data1);
    })
    .catch(error =>{
            console.error('Error:', error);
    })
}

Form.addEventListener('submit', (e) => {
    e.preventDefault();
    let data_hata = {
                       name : nom.value ,
                       color : couleur.value ,
                       icon : icone.value
                    } ;
    fetch('backend/api.php',
    {
     method: 'POST' ,
     headers: {'Content-Type': 'application/json'},
     body: JSON.stringify(data_hata)
    })
    .then(response => response.json())
    .then(data=>{
        console.log(data);
        Form.reset();
        showdata();
    })
    .catch(error =>{
        console.error('Error:', error);
    })
});
document.addEventListener('DOMContentLoaded', showdata);
document.getElementById("menu-item-24").classList.add('d-none')
// const $responsiveCarousel = document.querySelector(".js-carousel--responsive");


// new Glider($responsiveCarousel, {
//   slidesToShow: 1,
//   slidesToScroll: 1,
//   draggable: true,
//   dots: ".js-carousel--responsive-dots",
//   arrows: {
//     prev: ".js-carousel--responsive-prev",
//     next: ".js-carousel--responsive-next",
//   },
//   responsive: [
//     {
//       breakpoint: 600,
//       settings: {
//         slidesToShow: 2,
//         slidesToScroll: 2,
//       },
//     },
//     {
//       breakpoint: 900,
//       settings: {
//         slidesToShow: 3,
//         slidesToScroll: 3,
//       },
//     },
//   ],
// });

const modalEl = document.getElementById("modal-confirm-presence")


const daysEl = document.getElementById("days")
const hoursElLeft = document.getElementById("hours-left")
const hoursElRight = document.getElementById("hours-right")
const minsElLeft = document.getElementById("mins-left")
const minsElRight = document.getElementById("mins-right")
const secondsElLeft = document.getElementById("seconds-left")
const secondsElRight = document.getElementById("seconds-right")

const newYears = '1 Apr 2023'

function countdown(){

    const newYearsDate = new Date(newYears)
    const currentDate = new Date()
    
    const totalSeconds = (newYearsDate - currentDate) / 1000

    const days = Math.floor(totalSeconds / 3600 / 24)
    const hours = Math.floor(totalSeconds / 3600) % 24
    const mins = Math.floor(totalSeconds / 60) % 60
    const seconds = Math.floor(totalSeconds) % 60

    
    if(days > 0){
        daysEl.innerHTML = `Faltam ${days} dias e`
    }else{
        daysEl.innerHTML = `Faltam`
    }

    formatTime(hours, hoursElLeft, hoursElRight)
    formatTime(mins, minsElLeft, minsElRight)
    formatTime(seconds, secondsElLeft, secondsElRight)

}

function formatTime(time, elementLeft, elementRight){
    if(time >= 10)
    {
        time = time.toString()
        time = time.split('')
        elementLeft.innerHTML = time[0]
        elementRight.innerHTML = time[1]
    }else
    {
        elementLeft.innerHTML = 0
        elementRight.innerHTML = time
    }
}

if(modalEl){
  countdown()
  setInterval(countdown, 1000)
}


function openWhatsapp(phoneWhats, message) {
    let linkWhats
    let linkapp = 'https://wa.me/'+phoneWhats+'?text='+message;
    let linkweb = 'https://web.whatsapp.com/send?phone='+phoneWhats+'&text='+message;
    var userAgent = navigator.userAgent.toLowerCase();
    if( userAgent.search(/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i)!= -1 )
    { 
      linkWhats = linkapp
    }else{
      linkWhats = linkweb
    }

    window.open(encodeURI(linkWhats), '_blank');
    return false;
}

document.getElementById("whatsapp-link").addEventListener("click", function(e){
    e.preventDefault()
    openWhatsapp('65992402545', 'Olá Matheus Arruda da Onedev Desenvolvimentos!')
})

const nav=document.getElementById("nav-main-header")
if(nav)
{
  const header=document.getElementsByTagName("header")
  const topoNav = nav.offsetTop;

   function fixedMenu(){
    if(window.pageYOffset > topoNav){
      nav.classList.add("amille-party-bg-primary");
    } else if(window.pageYOffset <= topoNav){
      nav.classList.remove("amille-party-bg-primary");
    }
  }
  
  window.onscroll=function(){
    fixedMenu();  
  }

}







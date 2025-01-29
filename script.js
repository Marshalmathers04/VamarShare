const buttons = document.querySelectorAll(".footer__menu li")
const containers = document.querySelectorAll(".main__container")
    buttons.foreach((e,i)=>{
        e.addEventListener("click",()=>{
            containers.style.display = "none"
            containers[i].style.display = "flex"   
        })
})
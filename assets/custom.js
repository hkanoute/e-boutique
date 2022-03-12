import Swal from "sweetalert2";
import $ from 'jquery'

document.addEventListener("DOMContentLoaded",()=>{
    let cards = document.getElementsByClassName("product")
    $.ajax({
        url: 'products',
        method: "POST",
        success : (products)=>{
            let allProducts = document.getElementById("allProducts")
            let games = document.getElementById("games")
            let accesories = document.getElementById("accesories")
            let consoles = document.getElementById("consoles")

            allProducts.addEventListener("click", ()=> {
                products.forEach((product, i) =>{
                    cards[i].classList.remove('d-none')
                })
            })
            games.addEventListener("click", ()=> {
                products.forEach((product, i) =>{
                    cards[i].classList.remove('d-none')
                })
                let otherCards = Array.prototype.slice.call( cards ).filter(card => card.classList.contains("Game") === false)
                otherCards.forEach((card, i ) =>{
                    card.classList.add("d-none")
                })
            })
            accesories.addEventListener("click", ()=> {
                products.forEach((product, i) =>{
                    cards[i].classList.remove('d-none')
                })
                products.forEach((product, i) =>{
                    cards[i].classList.remove('d-none')
                })
                let otherCards = Array.prototype.slice.call( cards ).filter(card => card.classList.contains("Accessoires") === false)
                otherCards.forEach((card, i ) =>{
                    card.classList.add("d-none")
                })

            })
            consoles.addEventListener("click", ()=> {
                products.forEach((product, i) =>{
                    cards[i].classList.remove('d-none')
                })
                let otherCards = Array.prototype.slice.call( cards ).filter(card => card.classList.contains("Consoles") === false)
                otherCards.forEach((card, i ) =>{
                    card.classList.add("d-none")
                })


            })
            for (const card of cards){
                card.addEventListener("click",(cardEvent)=> {
                    console.log()
                    let cardClicked = cardEvent.currentTarget.children[0].children[0].children[0].children[1].children[0].children[0].textContent
                    let product = products.filter( product => product.name === cardClicked)
                    Swal.fire({
                        title : "Quantité",
                        confirmButtonText : "Valider",
                        html: `
                        <label>Veuillez spécifier la quantité</label>
                        <input type="text" id="quantity" class="swal2-input text-center">
                        `,
                        preConfirm() {
                            let quantity = parseInt(document.getElementById("quantity").value)
                            if (!quantity || !Number.isInteger(quantity)){
                                Swal.showValidationMessage("Veuillez indiquer une valeur correct !")
                            }
                            return {quantity : quantity}
                        }
                    }).then(result => {
                        if (result.isConfirmed){
                            let productNumber = parseInt(document.getElementById("validateCart").textContent)
                            productNumber++
                            document.getElementById("validateCart").textContent = productNumber.toString()
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top',
                                showConfirmButton: false,
                                timer: 1000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            })
                            Toast.fire({
                                icon: 'success',
                                title: 'Ajouté au panier !'
                            }).then(()=>{
                                $.ajax({
                                    url: "cart",
                                    method: "POST",
                                    data: { product: JSON.stringify(product), quantity : result.value.quantity},
                                })
                            })
                        }
                    })

                })
            }

        }
    })

})







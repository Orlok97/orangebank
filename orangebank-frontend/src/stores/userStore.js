import { defineStore } from "pinia";

export const useUserStore=defineStore('user',{
    state:()=>({
        test:'oksssss'
    }),
    actions:{
        async createUser(name, email,birthDate,CPF,password){
            const response=await fetch('http://127.0.0.1:8000/api/user',{
                method:'POST',
                headers:{
                    'Content-Type':'application/json'
                },
                body:JSON.stringify({
                    name:name,
                    email:email,
                    birthDate:birthDate,
                    CPF:CPF,
                    password:password
                })
            })
            if(!response){
                throw new Error('erro ao cadastrar o usuario')
            }
            const data=await response.json()
            console.log(data.response)
        }
    }
})
import { defineStore } from "pinia";

export const useAuthStore=defineStore('auth',{
    actions:{
        async login(email, password, router){
            const response = await fetch('http://127.0.0.1:8000/api/auth',{
                method:'POST',
                headers:{
                    'Content-Type':'application/json'
                },
                body:JSON.stringify({
                    email:email,
                    password:password
                })
            })
            if(!response.ok){
                throw new Error('erro ao efetuar o login')
            }
            const data=await response.json()
            sessionStorage.setItem('access_token',data.token)
            router.push('/dashboard')
            console.log(data.response)
        }
    }
})
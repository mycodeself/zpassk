# Zero PASSword Knowledge

zPASSk o Zero PASSword Knowledge es un gestor de contraseñas que utiliza un servidor de conocimiento cero que se encarga únicamente de persistir la información en la base de datos.

Toda la criptografía del sistema es realizada en la parte del cliente y se utiliza tanto criptografía simétrica como criptografía asimétrica para proteger la información. 

La información es protegida mediante una clave aleatoria utilizada para criptografía simétrica con AES-256 y esta clave es protegida con criptografía de curvas elípticas con la curva p256. La clave privada de ECC se protege a su vez también con AES-256 con un hash generado a partir de la contraseña del usuario en el sistema. Además, es posible compartir las credenciales con otros usuarios.

El proyecto se ha realizado con PHP + JavaScript 

---

zPASSk or Zero PASSword Knowledge is a password manager that uses a zero-knowledge server that only persists the information in the database.

All the cryptography of the system is carried out on the client side and both symmetric and asymmetric cryptography are used to protect the information. 

The information is protected by a random key used for symmetric cryptography with AES-256 and this key is protected with elliptical curve cryptography with the p256 curve. ECC's private key is also protected with AES-256 with a hash generated from the user's password in the system. In addition, it is possible to share credentials with other users.

The project has been done with PHP + JavaScript 

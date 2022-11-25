# sampleWebapp project 

* Visit website at https://huyvo.tech hosted on Google Cloud Platform & deploy Cloudflare WAF
* Synk security for vuln and code quality check
* SSL cerrtificate for https connection provided by zeroSSL & report at: https://www.ssllabs.com/ssltest/analyze.html?d=huyvo.tech&s=35.240.219.228
* Config apache server with SSL certificate
* Config apache redirect http to https and www to non-www
* Dockerize project
    - Command to compose the project: docker compose up
    NOTE: Please wait 1 minute
    - Command to stop the project: docker compose down
    - Access the project at: 
        + for wabsite: https://localhost:8080
        + for phpmyadmin: https://localhost:8443/phpmyadmin
            - root-username: root
            - root-password: secret
            - user-username: user
            - user-password: user

* Funtions *
- Login
- Register
- Top up balance
- Place order
- Upgrade account to VIP
- Vip account can place order with discount
- Online payment 
- Update profile


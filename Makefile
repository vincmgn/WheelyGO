run : 
	docker-compose up -d

docker-pt :
	docker-compose exec web sh

stop :
	docker-compose down
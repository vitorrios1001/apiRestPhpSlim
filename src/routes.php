<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
//Retorna lista com todos os clientes
$app->get('/todos', function ($request, $response, $args) {
    $sth = $this->db->prepare("SELECT * FROM Clientes ORDER BY id");
    $sth->execute();
    $todos = $sth->fetchAll();
    return $this->response->withJson($todos);
});

// Busca por id
$app->get('/id/[{id}]', function ($request, $response, $args) {
    $sth = $this->db->prepare("SELECT * FROM Clientes WHERE id=:id");
   $sth->bindParam("id", $args['id']);
   $sth->execute();
   $todos = $sth->fetchObject();
   return $this->response->withJson($todos);
});

//Adcionar novo cliente
$app->post('/addCliente', function ($request, $response) {
    $input = $request->getParsedBody();
    $sql = "INSERT INTO Clientes (Nome, Endereco, Bairro, Cidade, CEP, Ativo, Uf) 
                          values (:Nome, :Endereco, :Bairro, :Cidade, :CEP, :Ativo, :Uf)";
    $sth = $this->db->prepare($sql);
    $sth->bindParam("Nome", $input['Nome']);
    $sth->bindParam("Endereco", $input['Endereco']);
    $sth->bindParam("Bairro", $input['Bairro']);
    $sth->bindParam("Cidade", $input['Cidade']);
    $sth->bindParam("CEP", $input['CEP']);
    if ($input[':Ativo'] == null)
    {
        $sth->bindParam("Ativo", $input[null]);
    }else{
        $sth->bindParam("Ativo", $input[null]);  
    }           
    $sth->bindParam("Uf", $input['Uf']);
    $sth->execute();
    $input['id'] = $this->db->lastInsertId();
    return $this->response->withJson($input);
});

// Excluir cliente por id
$app->delete('/deleteCliente/[{id}]', function ($request, $response, $args) {
    $sth = $this->db->prepare("DELETE FROM Clientes WHERE id=:id");
    $sth->bindParam("id", $args['id']);
    $sth->execute();
    $sth = $this->db->prepare("SELECT * FROM Clientes ORDER BY id");
    $sth->execute();
    $todos = $sth->fetchAll();    
    return $this->response->withJson($todos);
});

// Atualizar cliente por id
$app->put('/putCliente/[{id}]', function ($request, $response, $args) {
    $input = $request->getParsedBody();
    $sql = "UPDATE Clientes SET Nome=:Nome,
                                Endereco=:Endereco,
                                Bairro=:Bairro,
                                Cidade=:Cidade,
                                CEP=:CEP,
                                Ativo=:Ativo,
                                Uf=:Uf    
                                WHERE id=:id";
     $sth = $this->db->prepare($sql);
    $sth->bindParam("id", $args['id']);
    $sth->bindParam("Nome", $input['Nome']);
    $sth->bindParam("Endereco", $input['Endereco']);
    $sth->bindParam("Bairro", $input['Bairro']);
    $sth->bindParam("Cidade", $input['Cidade']);
    $sth->bindParam("CEP", $input['CEP']);
    $sth->bindParam("Ativo", $input['Ativo']);
    $sth->bindParam("Uf", $input['Uf']);
    $sth->execute();
    $input['id'] = $args['id'];
    return $this->response->withJson($input);
});
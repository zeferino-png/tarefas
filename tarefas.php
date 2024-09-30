<?php
// Caminho para o arquivo onde as tarefas são armazenadas
$arquivo = 'tarefas.json';

// Função para carregar as tarefas do arquivo
function carregarTarefas() {
    global $arquivo;
    if (file_exists($arquivo)) {
        $conteudo = file_get_contents($arquivo);
        return json_decode($conteudo, true);
    }
    return [];
}

// Função para salvar as tarefas no arquivo
function salvarTarefas($tarefas) {
    global $arquivo;
    file_put_contents($arquivo, json_encode($tarefas));
}

// Carrega as tarefas existentes
$tarefas = carregarTarefas();

// Verifica a ação enviada via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    // Adicionar nova tarefa
    if ($action === 'add') {
        $novaTarefa = [
            'id' => uniqid(),
            'texto' => $_POST['text'],
            'completado' => false
        ];
        array_push($tarefas, $novaTarefa);
        salvarTarefas($tarefas);
        echo json_encode(['status' => 'success']);
        exit;
    }

    // Alternar o status de conclusão da tarefa
    if ($action === 'toggle') {
        $id = $_POST['id'];
        foreach ($tarefas as &$tarefa) {
            if ($tarefa['id'] === $id) {
                $tarefa['completado'] = !$tarefa['completado'];
                break;
            }
        }
        salvarTarefas($tarefas);
        echo json_encode(['status' => 'success']);
        exit;
    }

    // Excluir uma tarefa
    if ($action === 'delete') {
        $id = $_POST['id'];
        $tarefas = array_filter($tarefas, function ($tarefa) use ($id) {
            return $tarefa['id'] !== $id;
        });
        salvarTarefas($tarefas);
        echo json_encode(['status' => 'success']);
        exit;
    }
}

// Retorna as tarefas ao frontend
echo json_encode($tarefas);

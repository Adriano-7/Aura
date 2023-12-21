@extends('layouts.app')

@section('title', 'Política de Privacidade')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/static.css') }}">
@endsection

@section('header')
    @include('widgets.navBar')
@endsection

@section('content')
    <div class="container" id="staticCont">
        <div class="row">
            <div class="col-12">
                <h1 id="staticTitle">Política de Privacidade</h1>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <p id="staticText">
                    <strong>1. Introdução</strong>
                    <br>
                    <br>
                    A presente política de privacidade aplica-se a todos os dados pessoais recolhidos através do website Aura.
                    <br>
                    <br>
                    A Aura respeita a privacidade de todos os utilizadores do seu website e compromete-se a proteger as informações pessoais que cada utilizador decidir partilhar. Algumas secções e/ou funcionalidades deste website podem ser navegadas sem recurso a divulgação de qualquer informação pessoal por parte do utilizador.
                    <br>
                    <br>
                    No entanto, quando for necessária a recolha de informação pessoal para disponibilizar serviços ou quando cada utilizador decidir fornecer alguns dos seus dados pessoais, a utilização daquela informação e daqueles dados será efetuada no cumprimento da legislação aplicável sobre proteção de dados pessoais - Lei n.º 67/98, de 26 de Outubro, Lei de Proteção de Dados Pessoais - de forma a ser assegurada a confidencialidade e segurança dos dados pessoais fornecidos.
                    <br>
                    <br>
                    A entidade responsável pela recolha e tratamento de dados pessoais é a Aura.
                    <br>
                    <br>
                    A disponibilização de links para outros websites que não sejam da Aura é efetuada de boa fé, não podendo a Aura ser responsabilizada pela recolha e tratamento de dados pessoais efetuados nesses websites, nem ser responsabilizada pela exatidão, credibilidade e funcionalidades de websites pertencentes a terceiros.
                    <br>
                    <br>
                    <strong>2. O que são dados pessoais?</strong>
                    <br>
                    <br>
                    Dados pessoais são qualquer informação, de qualquer natureza e independentemente do respetivo suporte, incluindo som e imagem, relativa a uma pessoa singular identificada ou identificável.
                    <br>
                    <br>
                    É considerada identificável a pessoa que possa ser identificada direta ou indiretamente, designadamente por referência a um número de identificação ou a um ou mais elementos específicos da sua identidade física, fisiológica, psíquica, económica, cultural ou social.
                    <br>
                    <br>
                    <strong>3. Como são recolhidos os dados pessoais?</strong>
                    <br>
                    <br>
                    A Aura é a entidade responsável pela recolha e tratamento de dados pessoais, podendo, no âmbito da sua atividade, recorrer a entidades por si subcontratadas para a prossecução das finalidades aqui indicadas.
                    <br>
                    <br>
                    Por vezes, a prestação de determinados serviços envolve a transferência de dados pessoais para fora de Portugal. Nesses casos, a Aura cumprirá rigorosamente as disposições legais aplicáveis, nomeadamente quanto à determinação da adequabilidade de tal país no que respeita a proteção de dados pessoais e aos requisitos aplicáveis a tais transferências, incluindo, sempre que aplicável, a celebração dos contratos necessários para garantir a segurança dos dados pessoais transferidos e a proteção dos direitos dos titulares dos dados.
                    <br>
                    <br>

                </p>
            </div>
        </div>
    </div>
    
@endsection

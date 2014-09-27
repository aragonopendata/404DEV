package Rest::API;
use Dancer ':syntax';

use Encode;
use JSON::XS;

use Rest::Places;
use Rest::Social;
use Data::Dumper;
use Parallel::ForkManager;


our $VERSION = '0.1';

any ['post','get'] => '/search/' => sub {
	my $coordinates = {	lat =>params->{lat}, lng => params->{lng} };
    my $distance = params->{distance};
    my $name = params->{name} || '';
    my $maxPage = params->{nPages} || '5';

    my $placesApi = Rest::Places->new();
    $placesApi->init();
    #my $results->{places} = $placesApi->search($coordinates,$distance,$name);
    my $results = {};

    # API-SOCIAL-DATA BLOCK
    my $types = {twitter => 1, instagram => 1, flickr => 1};

    foreach my $type (keys %{$types}){
    	my $socialApi = Rest::Social->new({type => $type});
    	$socialApi->init();
    	$results->{$type} = $socialApi->process($coordinates, $distance/1000, $maxPage ,$name);
    }

    return JSON::XS->new->encode($results);
};


true;
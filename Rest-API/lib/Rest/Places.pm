package Rest::Places;

use strict;
use warnings;

use WWW::Mechanize;
use Data::Dumper;
use JSON qw/from_json/;
use Encode;

# AIzaSyC09vRj3Tls8TF8myHaEgmSNMZaQ3Ypu8M
# AIzaSyAjQ-GAazK7FWjzCT1o92i77q6ZLlOzIxU
# AIzaSyAzd2wmT2Drdw3mXJCqAlQgegQn8gpNrF0

sub new {
    my ( $class, $params ) = @_;
    my $objectRef = {
        API_KEY => 'AIzaSyC09vRj3Tls8TF8myHaEgmSNMZaQ3Ypu8M'
    };
    bless $objectRef, $class;
    return $objectRef;
}

sub init {
	my $self = shift;
	$self->{MECHANIZE} = WWW::Mechanize->new(autocheck => 0);

	return $self;
}

sub search
{
	my ($self,$coordinates,$distance,$location) = @_;

	# Search by name country 
	$coordinates = $self->_getLoc($location) if $location;
	my $searchResult = $self->_getPlaces($coordinates->{lat},$coordinates->{lng},$distance);
	return [] if !$searchResult;
	
	my $total = ();
	foreach(@$searchResult){	
		foreach(@{$_->{results}}){
			my $result = $self->_getPlaceInfo($_->{place_id}); 				
			push(@$total,$self->_parseResult($result));
		}
	}
	return $total; 
}


sub _parseResult
{
	my ($self, $result) = @_;
	my $name = $result->{result}->{name};
	$name = decode('iso-8859-1',$name);

	return {name => $name, lat => $result->{result}->{geometry}->{location}->{lat}, lng => $result->{result}->{geometry}->{location}->{lng}};
}

sub _getLoc {
	my ($self,$location) = @_;
	my $url = "http://maps.googleapis.com/maps/api/geocode/json?address=$location&sensor=false";
	my $response = $self->{MECHANIZE}->get($url);
	if($response->is_success){
		my $json = $response->content;
		my $hash; 
		eval { $hash = from_json($json)};
		if(!$@){
			return { lat => $hash->{results}[0]->{geometry}{location}{lat} , lng => $hash->{results}[0]->{geometry}{location}{lng} };
		}else{
			print "Error\n";
			print "$@\n";
		}
	}
}


sub _getPlaces {
	my ($self,$lat,$lng,$rad) = @_;
	$self->_prepareQuery($lat,$lng,$rad);
	my $response = $self->{MECHANIZE}->get($self->{QUERY_SEARCH});
	if($response->is_success){
		my $jsonString = $response->content;
		my $result = ();
		my $json = {}; 
		eval { $json = from_json($jsonString)};
		if(!$@){
			push(@$result,$json);
			while($json->{next_page_token}){
				last; # test mode only 1 call to places api
				$self->_nextPage($json->{next_page_token});
				sleep(1); 
				$response = $self->{MECHANIZE}->get($self->{QUERY_SEARCH});
				$jsonString = $response->content;
				eval {$json = from_json($jsonString)}; 
				push(@$result,$json);
			}
			return($result);
		}else{
			print "Error\n";
			print "$@\n";
		}
	}
}


sub _getPlaceInfo {
	my ($self,$placeid) = @_;
	my $url = "https://maps.googleapis.com/maps/api/place/details/json?placeid=$placeid&sensor=true&key=".$self->{API_KEY};
	my $response = $self->{MECHANIZE}->get($url);
	if($response->is_success){
		my $jsonString = $response->content;
		my $json; 
		eval { $json = from_json($jsonString)};
		if(!$@){
			return($json);
		}else{
			print "Error\n";
			print "$@\n";
		}
	}
}

sub _prepareQuery {
	my ($self,$lat,$lng,$rad) = @_;
	$self->{QUERY_SEARCH} = "https://maps.googleapis.com/maps/api/place/search/json?location=$lat,$lng&radius=$rad&key=".$self->{API_KEY}."&sensor=false";
}

sub _nextPage {
	my ($self, $nextPage) = @_;
	$self->{QUERY_SEARCH} =~ s/pagetoken=.*&?/pagetoken=$nextPage/ if $self->{QUERY_SEARCH} =~ /pageToken/;
	$self->{QUERY_SEARCH} = $self->{QUERY_SEARCH}."&pagetoken=$nextPage" if $self->{QUERY_SEARCH} !~ /pageToken/;
}

1;

package Utils;
use strict;
use warnings;

use JSON;
use WWW::Mechanize;

my $apiKey = "AIzaSyAzd2wmT2Drdw3mXJCqAlQgegQn8gpNrF0";

sub getLoc
{
	my $location = shift;
	my $url = "http://maps.googleapis.com/maps/api/geocode/json?address=$location&sensor=false";
	my $mech = WWW::Mechanize->new(autocheck => 0);
	my $response = $mech->get($url);
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

sub getPlaces
{
	my $search = shift;
	my $lat = shift;
	my $lng = shift;
	my $rad = shift;
	my $url = "https://maps.googleapis.com/maps/api/place/search/json?location=".$lat.",".$lng."&radius=".$rad."&keyword=".$search."&key=".$apiKey."&sensor=false";
	my $mech = WWW::Mechanize->new(autocheck => 0);
	my $response = $mech->get($url);
	if($response->is_success){
		my $json = $response->content;
		my $hash; 
		my $result = ();
		eval { $hash = from_json($json)};
		if(!$@){
			if(!$hash->{next_page_token}){
				push(@$result,$hash);
				return($result);
			}
			push(@$result,$hash);
			while($hash->{next_page_token}){
				sleep(1); 
				$url = "https://maps.googleapis.com/maps/api/place/search/json?location=".$lat.",".$lng."&radius=".$rad."&keyword=".$search."&key=".$apiKey."&sensor=false&pagetoken=".$hash->{next_page_token};
				$response = $mech->get($url);
				$json = $response->content;
				eval {$hash = from_json($json)}; 
				push(@$result,$hash);
			}
			return($result);
		}else{
			print "Error\n";
			print "$@\n";
		}
	}
}

sub getPlaceInfo
{
	my $reference = shift;
	my $url = "https://maps.googleapis.com/maps/api/place/details/json?reference=$reference&sensor=true&key=$apiKey";
	my $mech = WWW::Mechanize->new(autocheck => 0);
	my $response = $mech->get($url);
	if($response->is_success){
		my $json = $response->content;
		my $hash; 
		eval { $hash = from_json($json)};
		if(!$@){
			return($hash);
		}else{
			print "Error\n";
			print "$@\n";
		}
	}

}

=pod
=cut

1;

package GeoCrypto;

import java.io.BufferedReader;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.ArrayList;
import java.util.Arrays;

public class Encryptor {
	private Character[] characters = {'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','1','2','3','4','5','6','7','8','9','0','!','@','#','$','%','^','&','*','(',')','_','-','=','+','{','[','}',']','|','\\',':',';','\'','"',',','<','.','>','/','?',' '};
	int key;								//our standard list of encryptable characters and our key
	
	private Rotor rotor1;					//will use 4 rotor's to encrypt each character
	private Rotor rotor2;
	private Rotor rotor3;
	private Rotor rotor4;
	
	public Encryptor(double longitude, double latitude){
		double val1 = Math.abs(longitude - (int) longitude);	//get the positive fractional bits of
		double val2 = Math.abs(latitude - (int) latitude);		//our longitude and latitude
		
		val1 = val1*100;										//we take 2 digits from each val and add
		val2 = val2*100;										//them to create each individual key
		key = (int)(val1+val2);									//the individual key's are used for each rotor
		rotor1 = new Rotor(key%100);
		
		val1 = val1*100;										//we repeat the process for each rotor
		val2 = val2*100;										//moving the key up 2 digits and adding the
		key = key*100 + (int)(val1+val2)%100;					//new individual key
		rotor2 = new Rotor(key%100);							//by the end our key should be a 8+ digit number
		
		val1 = val1*100;
		val2 = val2*100;
		key = key*100 + (int)(val1+val2)%100;
		rotor3 = new Rotor(key%100);
		
		val1 = val1*100;
		val2 = val2*100;
		key = key*100 + (int)(val1+val2)%100;
		rotor4 = new Rotor(key%100);
	}
	public Encryptor(int settings){								//our constructor for when we already have the key
		key = settings;
		rotor1 = new Rotor((key/1000000)%100);					//we reverse the process, dividing it down and getting
		rotor2 = new Rotor((key/10000)%100);					//the 2 digit key's for each rotor
		rotor3 = new Rotor((key/100)%100);
		rotor4 = new Rotor(key%100);
	}
	public class Rotor{											//a rotor is what will encrypt an individual character
		private ArrayList<Character> rotor;						//the list of where each character is to an index value
		private ArrayList<Character> rotor_c;					//a copy rotor so we can revert increment changes
		private ArrayList<Character> chars;						//a copy of the standard characters as an arraylist
		int count;												//count will hold the number of times this rotor has incremented
		
		public Rotor(int n){									//will build our rotor
			count = 0;
			Character[] temp = new Character[93];				
			int i;
			for(i = 0;i<93;i++){								//will iterate 93 times for all 93 available characters
				if(n>92){										//we start with our key value as an index
					n = n-93;									//if its greater than the size we subtract it by 93
				}
				while(true){									//this keeps looping until it finds an empty slot
					if(n>92){
						n = n-93;
					}
					if(temp[n]==null){
						temp[n] = characters[i];				//when we find one we place the current character
						break;
					}
					n++;										//otherwise we move to the next slot and check
				}	
				n = (n+1)*2;
			}
			rotor = new ArrayList<Character>(Arrays.asList(temp));			//change everything to arraylists now
			rotor_c = rotor;												//because array's are easier to set up
			chars = new ArrayList<Character>(Arrays.asList(characters));	//but arraylists are convenient to implement
		}

		public Rotor(Character[] temp) {						//if we already have a list we can set it here
			rotor = new ArrayList<Character>(Arrays.asList(temp));
			rotor_c = rotor;
			chars = new ArrayList<Character>(Arrays.asList(characters));
		}
		public void increment(){								//simulates a rotor moving 1 notch over
			Character temp = rotor.remove(0);					//we do this by removing the first character
			rotor.add(temp);									//and moving it to the back
			count++;											//count increments keeping track number of times
		}
		public int getCount(){									//returns the current count
			return count;
		}
		public void reset(){									//resets the rotor to its original position
			rotor = rotor_c;									//then resets count back to 0
			count = 0;
		}
		public Character pushR(Character c){					//encrypts one character by getting the numerical value of
			return rotor.get(chars.indexOf(c));					//the character then using that as the index in the rotor
		}
		public Character pushL(Character c){					//reverse of pushL, can be used to decrypt a pushL character
			return chars.get(rotor.indexOf(c));					//or encrypt it even more
		}
		public void print_string(){								//just used for debugging to see the full rotor, by all means ignore
			int i;
			for(i=0;i<93;i++){
				System.out.print(rotor.get(i) + ", ");
			}
			System.out.println();
		}
	}
	
	public void encrypt(String filename){						//takes in a file and encrypts it
		ArrayList<Character>chars = new ArrayList<Character>(Arrays.asList(characters));
		String line;
		String new_line;
		try{
			FileReader filereader = new FileReader(filename);			//sets up the file reader
			BufferedReader reader = new BufferedReader(filereader);
			PrintWriter writer = new PrintWriter("encrypted.txt", "UTF-8");	//and our file writer which will create the encrypted file
			int i;
			char c;
			new_line = "";
			while((line = reader.readLine())!=null){					//reads our file line by line till we reach the end
				for(i=0;i<line.length();i++){							//iterates through the file line by each character
					c = line.charAt(i);
					if(chars.contains(c)){								//only encrypt it if the character is our list of encryptable characters
						c = rotor4.pushR(rotor3.pushR(rotor2.pushR(rotor1.pushR(c))));		//the character goes through rotor's 1 through 4 
						c = chars.get((chars.indexOf(c)+46)%93);							//gets reflected here, opposite side of the rotor (necessary otherwise the next line would just decrypt it)
						c = rotor1.pushL(rotor2.pushL(rotor3.pushL(rotor4.pushL(c))));		//then goes back through rotor's 4 through 1
						new_line += c;									//the character is added to the line
						rotor1.increment();
						if(rotor1.getCount()==93){						//increments each rotor accordingly and resets after they make a full turn
							rotor2.increment();
							rotor1.reset();
						}
						if(rotor2.getCount()==93){
							rotor3.increment();
							rotor2.reset();
						}
						if(rotor3.getCount()==93){
							rotor4.increment();
							rotor3.reset();	
						}
						if(rotor4.getCount()==93){
							rotor4.reset();
						}
					}
				}
				writer.println(new_line);				//writes the encrypted line into the new file
				new_line = "";
			}
			reader.close();
			writer.close();								//closes our new file
			rotor1.reset();								//reset our rotor's
			rotor2.reset();
			rotor3.reset();
			rotor4.reset();
		}
		catch(FileNotFoundException ex){
			System.out.println("Error finding file");
		}
		catch(IOException ex){
			System.out.println("Error reading file");
		}
		
	}
	public void decrypt(String filename){				//takes in a file and decrypts it
		ArrayList<Character>chars = new ArrayList<Character>(Arrays.asList(characters));
		String line;									//this method is almost exactly the same as encrypt() except for-
		String new_line;																//								|
		try{																			//								|
			FileReader filereader = new FileReader(filename);							//								|
			BufferedReader reader = new BufferedReader(filereader);						//								|
			PrintWriter writer = new PrintWriter("decrypted.txt", "UTF-8");				//								|
			int i;																		//								|
			char c;																		//								|
			new_line = "";																//								|
			while((line = reader.readLine())!=null){									//								|
				for(i=0;i<line.length();i++){											//								|
					c = line.charAt(i);													//								|
					if(chars.contains(c)){												//								|
						c = rotor4.pushR(rotor3.pushR(rotor2.pushR(rotor1.pushR(c))));	//								|
						c = chars.get((chars.indexOf(c)+47)%93);	//Here, just trust my math <-------------------------
						c = rotor1.pushL(rotor2.pushL(rotor3.pushL(rotor4.pushL(c))));
						new_line += c;
						rotor1.increment();
						if(rotor1.getCount()==93){
							rotor2.increment();
							rotor1.reset();
						}
						if(rotor2.getCount()==93){
							rotor3.increment();
							rotor2.reset();
						}
						if(rotor3.getCount()==93){
							rotor4.increment();
							rotor3.reset();	
						}
						if(rotor4.getCount()==93){
							rotor4.reset();
						}
					}
				}
				writer.println(new_line);
				new_line = "";
			}
			reader.close();
			writer.close();
		}
		catch(FileNotFoundException ex){
			System.out.println("Error finding file");
		}
		catch(IOException ex){
			System.out.println("Error reading file");
		}
	}
	public int getKey(){
		return key;
	}
	
	public static void main(String[] args){				//sample implementation, put in the necessary values and try it
		//Encryptor encryptor = new Encryptor(Longitude,Latitude);
		//Encryptor encryptor = new Encryptor(key);
		//String filename = "encrypt_me.txt";
		//encryptor.encrypt(filename);
		//filename = "encrypted.txt";
		//encryptor.decrypt(filename);
	}
}
